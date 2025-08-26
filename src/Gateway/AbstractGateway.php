<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 26/08/2025
 **/

namespace App\Gateway;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly abstract class AbstractGateway
{
    public function __construct(
        protected readonly HttpClientInterface $httpClient,                   // idealmente já “Retryable”
        protected readonly SerializerInterface $serializer,
        protected readonly LoggerInterface     $logger,
        private readonly string                $baseUrl,                               // ex: '%env(IDP_BASE_URL)%'
        private readonly array                 $defaultHeaders = []
    )
    {
    }

    /** Ex: 'idp', 'billing', etc. Apenas para logs/metrics. */
    abstract protected function serviceName(): string;

    /** Timeout padrão em segundos (pode sobrescrever por serviço) */
    protected function defaultTimeout(): float
    {
        return 5.0;
    }

    /** Máximo de bytes aceitos na resposta (0 = ilimitado) */
    protected function maxBodySize(): int
    {
        return 0;
    }

    /**
     * Requisição síncrona com conveniências:
     * - monta URL base + path
     * - injeta headers (JSON, UA, Authorization, Correlation-Id)
     * - aceita 'query', 'json', 'body', 'files'
     * - desserializa para $responseClass se informado (JSON)
     */
    protected function request(
        string  $method,
        string  $path,
        array   $options = [],
        ?string $responseClass = null
    ): mixed
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');

        // Opções base
        $httpOptions = [
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'nt4-gateway/' . $this->serviceName(),
            ],
        ];

        try {
            $response = $this->httpClient->request($method, $url, $httpOptions);
            $status = $response->getStatusCode();

            if ($status >= 400) {
                $this->throwHttpError($response);
            }

            if ($responseClass === null) {
                // Retorna array se JSON, senão string
                $contentType = $response->getHeaders()['content-type'][0] ?? '';
                $content = $response->getContent(false);

                if (str_contains($contentType, 'application/json')) {
                    return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
                }
                return $content;
            }

            $json = $response->getContent(false);
            return $this->serializer->deserialize($json, $responseClass, 'json');
        } catch (ClientException|ServerException $e) {
            // Exceptions já representam 4xx/5xx; padronize:
            if ($e instanceof ClientException || $e instanceof ServerException) {
                /** @var ResponseInterface $resp */
                $resp = $e->getResponse();
                $this->throwHttpError($resp, $e);
            }
            throw $e; // fallback
        } catch (TransportExceptionInterface $e) {
            // DNS, timeouts duros, TLS, etc.
            $this->logger->error('Transport error calling ' . $this->serviceName(), [
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'url' => $url,
            ]);
            throw $e;
        }
    }

    /** Versão assíncrona (streaming/await elsewhere) */
    protected function requestAsync(string $method, string $path, array $options = []): ResponseInterface
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');

        $headers = array_merge([
            'Accept' => 'application/json',
            'User-Agent' => 'nt4-gateway/' . $this->serviceName()]);

        $httpOptions = [
            'headers' => $headers,
            'query' => $options['query'] ?? [],
            'json' => $options['json'] ?? null,
            'body' => $options['body'] ?? null,
            'timeout' => $options['timeout'] ?? $this->defaultTimeout(),
            'extra' => $options['extra'] ?? [],
            'max_buffer_size' => $options['max_buffer_size'] ?? $this->maxBodySize(),
        ];

        return $this->httpClient->request($method, $url, $httpOptions);
    }

    private function throwHttpError(ResponseInterface $response, ?\Throwable $previous = null): never
    {
        $status = $response->getStatusCode();
        $headers = $response->getHeaders(false);
        $contentType = $headers['content-type'][0] ?? '';
        $raw = $response->getContent(false);
        $problem = null;

        if (str_contains($contentType, 'application/problem+json') || str_contains($contentType, 'application/json')) {
            $problem = json_decode($raw, true);
            if (!is_array($problem)) {
                $problem = null;
            }
        }

        throw new GatewayHttpException($status, $headers, $problem, $raw, previous: $previous);
    }
}
