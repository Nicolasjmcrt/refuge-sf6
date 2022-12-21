<?php

namespace App\Security\Voter;

use App\Entity\Animals;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AnimalsVoter extends Voter
{
    const EDIT = 'ANIMAL_EDIT';
    const DELETE = 'ANIMAL_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $animal): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $animal instanceof Animals;
    }

    protected function voteOnAttribute($attribute, $animal, TokenInterface $token): bool
    {
        // On récupère l'utilisateur connecté
        $user = $token->getUser();

        // Si l'utilisateur n'est pas connecté, on refuse l'accès
        if(!$user instanceof UserInterface) {
            return false;
        }

        // Si l'utilisateur est admin, on lui donne accès
        if($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // On vérifie les permissions
        switch($attribute) {
            case self::EDIT:
                // On vérifie que l'utilisateur peut éditer l'animal
                return $this->canEdit();
                break;
            case self::DELETE:
                // On vérifie que l'utilisateur peut supprimer l'animal
                return $this->canDelete();
                break;
        }
    }

    private function canEdit()
    {
        return $this->security->isGranted('ROLE_ANIMAL_ADMIN');
    }

    private function canDelete()
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }

}