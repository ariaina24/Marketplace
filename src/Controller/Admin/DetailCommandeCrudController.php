<?php

namespace App\Controller\Admin;

use App\Entity\DetailCommande;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DetailCommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DetailCommande::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
