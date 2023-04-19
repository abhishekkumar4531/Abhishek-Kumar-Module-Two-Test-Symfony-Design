<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AddedItem;
use App\Entity\MarkedItem;
use Doctrine\ORM\EntityManagerInterface;

/**
 * HomeController
 * This class is extends from AbstractController.
 * Ther is default controller class.
 * All required operations like add, edit, delete and marked will be perform
 * through this class only.
 * There are multiple functions in this class, all are define for a single
 * operations.
 */
class HomeController extends AbstractController {

  public $entityManager;
  public function __construct(EntityManagerInterface $entityManager) {
    $this->entityManager = $entityManager;
  }

  #[Route('/', name: 'app_homes')]
  #[Route('/home', name: 'app_home')]
  /**
   * index - This function is responsible for showing the data to user.
   * It will render to home page.
   *
   * @return Response
   */
  public function index(): Response {
    return $this->render('home/index.html.twig', []);
  }

  #[Route('/home/add', name: 'app_add')]
  /**
   * addItem
   * It is responsible for adding the items in unmarked item list.
   *
   * @return void
   */
  public function addItem() {
    $newItem = $_POST['newItem'];
    $newItem = htmlspecialchars($newItem);
    $add = new AddedItem();
    if($newItem != "") {
      $add->setItemValue($newItem);
      $this->entityManager->persist($add);
      $this->entityManager->flush();
      return $this->json(['status' => TRUE]);
    }
    else {
      return $this->json(['status' => FALSE]);
    }
  }

  #[Route('/home/display', name: 'app_display')]
  /**
   * displayItem
   * It is reposible for displaying the un-marked item list.
   *
   * @param  mixed $entityManager
   * @return void
   */
  public function displayItem() {
    $verify = $this->entityManager->getRepository(AddedItem::class);
    $fetchAll = $verify->findAll();
    if(!$fetchAll) {
      return $this->render('home/added.html.twig', [
        'addedList' => $fetchAll,
        'message' => 'Heyy currently list is empty!!!'
      ]);
    }
    else {
      return $this->render('home/added.html.twig', [
        'addedList' => $fetchAll
      ]);
    }
  }

  #[Route('/home/edit', name: 'app_edit')]
  /**
   * editItme
   * It is responsible for edditing the unmarked item list.
   *
   * @return void
   */
  public function editItem() {
    $editId = $_POST['editItemId'];
    $editItem = $_POST['editItemValue'];
    $verify = $this->entityManager->getRepository(AddedItem::class);
    $checkId = $verify->findOneBy([ 'id' => $editId ]);
    if($checkId) {
      $checkId->setItemValue($editItem);
      $this->entityManager->flush();
      return $this->json(['status' => TRUE]);
    }
    else {
      return $this->json(['status' => FALSE]);
    }
  }

  #[Route('/home/mark', name: 'app_mark')]
  /**
   * markItem
   * It is responsible for send the data to marked item list and remove from
   * unmarked item list.
   *
   * @return void
   */
  public function markItem() {
    $newItem = $_POST['markItemValue'];
    $newItem = htmlspecialchars($newItem);
    $add = new MarkedItem();
    if($newItem != "") {
      $add->setItemValue($newItem);
      $this->entityManager->persist($add);
      $this->entityManager->flush();
      return $this->json(['status' => TRUE]);
    }
    else {
      return $this->json(['status' => FALSE]);
    }
  }

  #[Route('/home/delete', name: 'app_delete')]
  /**
   * deleteItem
   * It is responsible for deleteing the item from un-marked item list.
   *
   * @return void
   */
  public function deleteItem() {
    $itemId = $_POST['delItemId'];
    $verify = $this->entityManager->getRepository(AddedItem::class);
    $checkId = $verify->findOneBy([ 'id' => $itemId ]);
    if($checkId) {
      $this->entityManager->remove($checkId);
      $this->entityManager->flush();
      return $this->json(['status' => TRUE]);
    }
    else {
      return $this->json(['status' => FALSE]);
    }
  }

  #[Route('/home/displaymarked', name: 'app_displaymarked')]
  /**
   * displayMarked
   * It is responsible for display the marked item list.
   *
   * @return void
   */
  public function displayMarked() {
    $verify = $this->entityManager->getRepository(MarkedItem::class);
    $fetchAll = $verify->findAll();
    if(!$fetchAll) {
      return $this->render('home/marked.html.twig', [
        'markedList' => $fetchAll,
        'message' => 'Heyy currently marked list is empty!!!'
      ]);
    }
    else {
      return $this->render('home/marked.html.twig', [
        'markedList' => $fetchAll
      ]);
    }
  }

  #[Route('/home/deletemarked', name: 'app_delete_marked')]
  /**
   * deleteMarked
   * It is responsible for delete the item from marked item list.
   *
   * @return void
   */
  public function deleteMarked() {
    $itemId = $_POST['delItemId'];
    $verify = $this->entityManager->getRepository(MarkedItem::class);
    $checkId = $verify->findOneBy([ 'id' => $itemId ]);
    if($checkId) {
      $this->entityManager->remove($checkId);
      $this->entityManager->flush();
      return $this->json(['status' => TRUE]);
    }
    else {
      return $this->json(['status' => FALSE]);
    }
  }
}
