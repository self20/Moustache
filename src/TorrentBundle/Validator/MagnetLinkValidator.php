<?php

declare(strict_types=1);

namespace TorrentBundle\Validator;

use Rico\Lib\ValidationUtils;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MagnetLinkValidator extends ConstraintValidator
{
    /**
     * @var ValidationUtils
     */
    private $validationUtils;

    /**
     * @param ValidationUtils $validationUtils
     */
    public function __construct(ValidationUtils $validationUtils)
    {
        $this->validationUtils = $validationUtils;
    }

   /**
    * {@inheritdoc}
    */
   public function validate($value, Constraint $constraint)
   {
       if (!$this->validationUtils->isURLMagnet($value)) {
           $this->context->buildViolation($constraint->message)->addViolation();
       }
   }
}
