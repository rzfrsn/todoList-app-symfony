<?php

namespace App\Controller;

use App\Entity\TodoList;
use App\Entity\TodoListItem;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoListItemController extends AbstractController
{
    /**
     * @Route("/todo/list/item/create", name="todo_list_item_create", methods={"POST"})
     */
    public function create (Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $todoListItem = new TodoListItem();
        $todoListItem->setName($request->request->get('todoListItemName'));
        $todoListItem->setTodoList(
            $entityManager->getRepository(TodoList::class)
                        ->find($request->request->get('todoListId'))
        );
        
        $entityManager->persist($todoListItem);
        $entityManager->flush();
        
        return $this->redirectToRoute('todo_list');
    }

    /**
     * @Route("/todo/list/item/{id}/delete", name="todo_list_item_delete")
     */
    public function delete(TodoListItem $todoListItem, ManagerRegistry $doctrine) {
        $entityManager = $doctrine->getManager();

        $entityManager->remove($todoListItem);
        $entityManager->flush();

        return $this->redirectToRoute('todo_list');
    }
}