<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Article;
use App\Entity\Banner;
use App\Entity\CategoryArticle;
use App\Entity\CategoryProfessional;
use App\Entity\City;
use App\Entity\Comment;
use App\Entity\Country;
use App\Entity\KeywordArticle;
use App\Entity\Language;
use App\Entity\Menu;
use App\Entity\Need;
use App\Entity\Page;
use App\Entity\Professional;
use App\Entity\Region;
use App\Entity\Service;
use App\Entity\SocialUrl;
use App\Entity\Testimonial;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'users' => []
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Pambe');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
                    ->overrideTemplates([
                        'layout' => 'admin/layout.html.twig',
                        'main_menu' => 'admin/menu.html.twig',
                        'crud/index' => 'admin/crud/index.html.twig',
                    ])
                    ->showEntityActionsInlined()
                    ->setDefaultSort(['id' => 'DESC'])
                    ->setEntityPermission('ROLE_ADMIN');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
                        ->addHtmlContentToHead('<link rel="preconnect" href="https://fonts.gstatic.com">')
                        ->addHtmlContentToHead('<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&amp;display=swap" rel="stylesheet">')
                        ->addHtmlContentToHead('<link href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css" rel="stylesheet">')
                        ->addCssFile('assets/css/bootstrap.css')
                        ->addCssFile('assets/vendors/iconly/bold.css')
                        ->addCssFile('assets/vendors/perfect-scrollbar/perfect-scrollbar.css')
                        ->addCssFile('assets/vendors/bootstrap-icons/bootstrap-icons.css')
                        ->addCssFile('assets/css/app.css')
                        ->addHtmlContentToHead('<style>.list-pagination-counter{float: right;}#app{display:flex;}#main{width:100%;}.btn.btn-secondary{color:black;}.sidebar-wrapper{position:fixed;}body{background-color:#f2f7ff;font-family:Nunito;}</style>')
                        ->addJsFile('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js')
                        ->addJsFile('assets/js/bootstrap.bundle.min.js')
                        ->addJsFile('assets/vendors/apexcharts/apexcharts.js')
                        ->addJsFile('assets/js/pages/dashboard.js')
                        ->addJsFile('assets/js/main.js')
                        ;
    }
    
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Tableau de bord', 'bi bi-grid-fill');
        yield MenuItem::section('Catalogue');
        yield MenuItem::linkToCrud('Catégories', 'bi bi-wallet-fill', CategoryProfessional::class);
        yield MenuItem::linkToCrud('Professionnels', 'bi bi-person-lines-fill', Professional::class);
        yield MenuItem::linkToCrud('Services', 'bi bi-person-bounding-box', Service::class);
        yield MenuItem::section('Zone');
        yield MenuItem::linkToCrud('Pays', 'bi bi-geo-alt-fill', Country::class);
        yield MenuItem::linkToCrud('Régions', 'bi bi-geo-alt-fill', Region::class);
        yield MenuItem::linkToCrud('Villes', 'bi bi-geo-alt-fill', City::class);
        yield MenuItem::section('Blog');
        yield MenuItem::linkToCrud('Catégories', 'bi bi-pin', CategoryArticle::class);
        yield MenuItem::linkToCrud('Mots Clés', 'bi bi-pin', KeywordArticle::class);
        yield MenuItem::linkToCrud('Articles', 'bi bi-pin', Article::class);
        yield MenuItem::linkToCrud('Commentaires', 'bi bi-pin', Comment::class);
        yield MenuItem::section('Configuration');
        yield MenuItem::linkToCrud('Langues', 'bi bi-pin', Language::class);
        yield MenuItem::linkToCrud('Réseaux sociaux', 'bi bi-pin', SocialUrl::class)->setAction(Crud::PAGE_EDIT)->setEntityId(1);
        yield MenuItem::linkToCrud('Témoignages', 'bi bi-pin', Testimonial::class);
        yield MenuItem::linkToCrud('Bannières', 'bi bi-pin', Banner::class);
        yield MenuItem::linkToCrud('Administrateurs', 'bi bi-pin', Admin::class);
        yield MenuItem::linkToCrud('Pages', 'bi bi-pin', Page::class);
        yield MenuItem::linkToCrud('Menus', 'bi bi-pin', Menu::class);
        yield MenuItem::linkToCrud('Besoins', 'bi bi-pin', Need::class);
        yield MenuItem::section();
        yield MenuItem::linkToLogout('Déconnexion', 'bi bi-x-circle');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
                        ->displayUserName(true)
                        ->displayUserAvatar(true);
    }

   public function configureActions(): Actions
    {
        return Actions::new()
                        ->add(Crud::PAGE_INDEX, Action::DETAIL)
                        ->add(Crud::PAGE_INDEX, Action::NEW)
                        ->add(Crud::PAGE_INDEX, Action::EDIT)
                        ->add(Crud::PAGE_INDEX, Action::DELETE)
                        ->add(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN)
                        ->add(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
                        ->add(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
                        ->add(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);
    }
}
