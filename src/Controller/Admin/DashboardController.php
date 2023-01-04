<?php

namespace App\Controller\Admin;

use App\Entity\Animals;
use App\Entity\Refuges;
use App\Entity\Testimony;
use App\Entity\Sponsorships;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin/index.html.twig');
        # return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Animals Shelter Admin');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {

        if (!$user instanceof Users) {
            throw new \Exception('Wrong user');
        }
        return parent::configureUserMenu($user)
            ->setMenuItems([
                MenuItem::linkToUrl('My Profile', 'fa fa-user', $this->generateUrl('profile_index'))
            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Homepage', 'fa fa-home', $this->generateUrl('main'));
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        yield MenuItem::linkToCrud('Refuges', 'fa fa-shield-cat', Refuges::class);
        yield MenuItem::linkToCrud('Sponsorships', 'fa fa-money-check-dollar', Sponsorships::class);
        yield MenuItem::linkToCrud('Testimonies', 'fa fa-comments', Testimony::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
