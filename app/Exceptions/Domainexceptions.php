<?php

namespace App\Exceptions;

class TicketCodeException extends AppException
{
    public static function notFound(): static
    {
        return new static('Code ticket introuvable.', 404);
    }

    public static function alreadyUsed(): static
    {
        return new static('Ce code ticket a déjà été utilisé.', 409);
    }

    public static function invalid(): static
    {
        return new static('Code ticket invalide.', 422);
    }
}

class ParticipationException extends AppException
{
    public static function alreadyParticipated(): static
    {
        return new static('Vous avez déjà participé avec ce code.', 409);
    }

    public static function notFound(): static
    {
        return new static('Participation introuvable.', 404);
    }
}

class PrizeException extends AppException
{
    public static function outOfStock(): static
    {
        return new static('Ce lot n\'est plus disponible (rupture de stock).', 409);
    }

    public static function notFound(): static
    {
        return new static('Lot introuvable.', 404);
    }
}

class RedemptionException extends AppException
{
    public static function alreadyRedeemed(): static
    {
        return new static('Ce lot a déjà été réclamé.', 409);
    }

    public static function notFound(): static
    {
        return new static('Réclamation introuvable.', 404);
    }
}

class AuthException extends AppException
{
    public static function invalidCredentials(): static
    {
        return new static('Identifiants incorrects.', 401);
    }

    public static function accountNotFound(): static
    {
        return new static('Aucun compte associé à cet email.', 404);
    }
}