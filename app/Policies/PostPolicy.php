<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * L'admin a tous les droits.
     * On accepte ?User pour supporter les invités (null).
     */
    public function before(?User $user, string $ability): ?bool
    {
        if ($user && $user->isAdmin()) {
            return true;
        }
        return null; // continue vers les méthodes spécifiques
    }

    /**
     * Tout le monde (invité compris) peut lister.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * On n'autorise la vue que pour les articles publiés.
     * (Les admins passent déjà par before()).
     */
    public function view(?User $user, Post $post): bool
    {
        return $post->status === 'published';
    }

    /**
     * Création/édition/suppression : réservé aux admins (géré par before()).
     * On retourne false par défaut pour les non-admins.
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Post $post): bool
    {
        return false;
    }

    public function delete(User $user, Post $post): bool
    {
        return false;
    }

    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
