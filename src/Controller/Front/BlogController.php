<?php

namespace App\Controller\Front;

use App\Entity\Article;
use App\Entity\CategoryArticle;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Service\BlogService;
use App\Service\ContextService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogController extends AbstractController
{
    private $context;
    private $paginator;
    private $blogService;
    private $translator;

    public function __construct(ContextService $context, PaginatorInterface $paginator, BlogService $blogService, TranslatorInterface $translator)
    {
        $this->context = $context;
        $this->paginator = $paginator;
        $this->blogService = $blogService;
        $this->translator = $translator;
    }

    public function index(Request $request): Response
    {
        $data = $this->blogService->getAllArticle();

        $articles = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('front/blog/index.html.twig', compact('articles'));
    }

    public function show(Article $article, Request $request): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            
            $comment->setArticle($article);
            $this->context->save($comment);

            $message = $this->translator->trans('global.your_comment_is_awaiting_moderation');

            $this->addFlash('success', $message);
            
            return $this->redirectToRoute('app_blog_article', ["slug" => $article->getSlug()]);
        }

        $comments = $this->blogService->getAllComments($article);
        $commentForm = $commentForm->createView();
        return $this->render('front/blog/show.html.twig', compact('article', 'comments', 'commentForm'));
    }

    public function category(CategoryArticle $category, Request $request): Response
    {
        $data = $category->getArticles();
        $articles = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('front/blog/category/index.html.twig', compact('category', 'articles'));
    }

    public function search(Request $request): Response
    {
        $search = $request->query->get('words');

        $data = $this->blogService->search($search);

        $articles = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('front/blog/search/index.html.twig', compact('articles'));
    }
}
