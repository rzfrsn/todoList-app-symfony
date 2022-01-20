<?php

namespace App\Controller;

use App\Entity\TodoList;
use App\Entity\TodoListItem;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends AbstractController
{
    /**
     * @Route("/todo/list", name="todo_list")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $allTodoList = $doctrine->getRepository(TodoList::class)->findAll();
        $todoList = new TodoList();
        $todoListItem = new TodoListItem();

        //creating forms
        $todoListForm = $this->createFormBuilder($todoList)
                    ->add('title', TextType::class)
                    ->add('save', SubmitType::class, ['label' => 'create TodoList'])
                    ->getForm();
        
        //getting request data with form
        $todoListForm->handleRequest($request);

        if($todoListForm->isSubmitted() && $todoListForm->isValid()) {
            $todoList = $todoListForm->getData();
            $entityManager = $doctrine->getManager();

            $entityManager->persist($todoList);
            $entityManager->flush($todoList);

            return $this->redirectToRoute('todo_list');
        }

        return $this->render('todo_list/index.html.twig', [
            'controller_name' => 'TodoListController',
            'allTodoList' => $allTodoList,
            'todoListForm' => $todoListForm->createView()
        ]);
    }

    /**
     * @Route("/todo/list/{id}/delete", name="todo_list_delete")
     */
    public function delete(TodoList $todoList, ManagerRegistry $doctrine) {
        $entityManager = $doctrine->getManager();

        $entityManager->remove($todoList);
        $entityManager->flush();

        return $this->redirectToRoute('todo_list');
    }

}
