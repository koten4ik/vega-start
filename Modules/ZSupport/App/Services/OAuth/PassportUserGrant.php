<?php

namespace Modules\ZSupport\App\Services\OAuth;

use DateInterval;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\RequestEventHandlerInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;

class PassportUserGrant extends AbstractGrant
{
	public function __construct(
		UserRepository $userRepository,
		RefreshTokenRepository $refreshTokenRepository
	) {
		$this->setUserRepository($userRepository);
		$this->setRefreshTokenRepository($refreshTokenRepository);
		$this->accessTokenTTL = Passport::tokensExpireIn() ?? new \DateInterval('PT1H');
		$this->refreshTokenTTL = Passport::refreshTokensExpireIn() ?? new \DateInterval('P1M');
	}

	public function getIdentifier(): string
	{
		return 'user_grant';
	}

	public function respondToAccessTokenRequest(
		ServerRequestInterface $request,
		ResponseTypeInterface $responseType,
		DateInterval $accessTokenTTL
	): ResponseTypeInterface {
		$userId = $request->getParsedBody()['user_id'] ?? null;
		if (!$userId) {
			throw OAuthServerException::invalidRequest('user_id');
		}

		$clientId = $request->getParsedBody()['client_id'] ?? null;
		$clientRepository = app(ClientRepository::class);
		$client = $clientRepository->getClientEntity($clientId);

		$scopes = $this->validateScopes($request->getParsedBody()['scope'] ?? '');
		$accessToken = $this->issueAccessToken(
			$accessTokenTTL,
			$client,   // <- объект клиента
			$userId,
			$scopes
		);

		$refreshToken = $this->issueRefreshToken($accessToken);

		$responseType->setAccessToken($accessToken);
		$responseType->setRefreshToken($refreshToken);

		return $responseType;
	}
}
