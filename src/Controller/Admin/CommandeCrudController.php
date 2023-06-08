<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add('index','detail');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id'=> 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('dateCommande'),
            TextField::new('user.nom'),
            MoneyField::new('Total')->setCurrency('MGA'),
            TextField::new('transporteurName', 'Transporteur'),
            MoneyField::new('transporteurPrix', 'Frais de port')->setCurrency('MGA'),
            BooleanField::new('Paye', 'Payée'),
            ArrayField::new('commandeDetail', 'Produits achetés')->hideOnIndex()
        ];
    }

}
