<?php

namespace App\Controller\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use App\Entity\Produit;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Core\Security;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function configureFields(string $pageName): iterable
    {
        $user = $this->security->getUser();
        $fields = [
            TextField::new('nom'),
            SlugField::new('slug')->setTargetFieldName('nom'),
            ImageField::new('illustration')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            MoneyField::new('prix')->setCurrency('MGA'),
            NumberField::new('nombre'),
            AssociationField::new('categorie'),
        ];

        if (Crud::PAGE_INDEX === $pageName) {
            $fields[] = AssociationField::new('vendeur')
                ->setFormTypeOption('choices', [$user])
                ->setRequired(true);
        } else {
            $fields[] = AssociationField::new('vendeur')
                ->setFormTypeOption('choices', [$user]);
        }

        return $fields;
    }
}
