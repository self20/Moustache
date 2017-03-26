<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

class SignupController
{
    /**
     * @param string $confirmationToken
     *
     * @return string
     */
    public function formAction(string $confirmationToken): string
    {
        // Vérifier que l’utilisateur $signupToken existe en base
        // Sinon 403

        // Afficher le formulaire de signup
        // Qui contient le username, mais non modifiable
        // Password et password confirmation
    }

    /**
     * @param string $confirmationToken
     */
    public function signupAction(string $confirmationToken)
    {
        // Vérifier que l’utilisateur $signupToken existe en base
        // Sinon 403

        // handle form request
        // If submitted & valide
        // Update de l’utilisateur (password et enable et remove du confirmationToken)
    }
}
