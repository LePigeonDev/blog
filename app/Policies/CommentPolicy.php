<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Donne tous les droits à l'admin.
     * ?User permet de gérer les invités (null).
     */
    public function before(?User $user, string $ability): ?bool
    {
        if ($user && $user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Tout le monde peut lister les commentaires (le contrôleur filtrera selon la conf).
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Un commentaire est visible si approuvé, ou par son auteur connecté.
     * (Les invités ne voient que les approuvés, et encore selon le contrôleur.)
     */
    public function view(?User $user, Comment $comment): bool
    {
        if ($comment->status === 'approved') {
            return true;
        }

        return $user?->id === $comment->user_id;
    }

    /**
     * Utilisateur connecté peut créer.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Auteur (ou admin via before) peut mettre à jour.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isAdmin();
    }

    /**
     * Auteur (ou admin via before) peut supprimer.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isAdmin();
    }

    public function restore(User $user, Comment $comment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
        return false;
    }

    /**
     * Capacité personnalisée pour la modération (approuver/masquer/supprimer).
     * Utilise $this->authorize('moderate', Comment::class) côté contrôleur admin.
     */
    public function moderate(User $user): bool
    {
        return $user->isAdmin();
    }
}
