<?php

namespace FOS\CommentBundle\Tests\Functional\Bundle\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    public function asyncAction($id)
    {
        return $this->render('CommentBundle:Comment:async.html.twig', array(
            'id' => $id,
        ));
    }

    public function inlineAction(Request $request, $id)
    {
        $thread = $this->container->get('fos_comment.manager.thread')->findThreadById($id);
        if (null === $thread) {
            $thread = $this->container->get('fos_comment.manager.thread')->createThread();
            $thread->setId($id);
            $thread->setPermalink($request->getUri());

            // Add the thread
            $this->container->get('fos_comment.manager.thread')->saveThread($thread);
        }

        $comments = $this->container->get('fos_comment.manager.comment')->findCommentTreeByThread($thread);

        return $this->render('CommentBundle:Comment:inline.html.twig', array(
            'comments' => $comments,
            'displayDepth' => null,
            'sorter' => null,
            'thread' => $thread,
            'view' => 'tree',
        ));
    }
}
