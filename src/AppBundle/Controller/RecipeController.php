<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Recipe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;use Symfony\Component\HttpFoundation\Request;

/**
 * Recipe controller.
 *
 * @Route("/")
 */
class RecipeController extends Controller
{
    /**
     * Lists all recipe entities.
     *
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $recipes = $em->getRepository('AppBundle:Recipe')->findAll();

        return $this->render('recipe/index.html.twig', array(
            'recipes' => $recipes,
        ));
    }

    /**
     * Creates a new recipe entity.
     *
     * @Route("/new", name="recipe_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $recipe = new Recipe();
        $form = $this->createForm('AppBundle\Form\RecipeType', $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            for ($i = 1; $i <= 5; $i++) {
                $arrIng[] = ($form['ingredient' . $i]->getData());
            }
            if ($recipe->getPicture()) {
                $file = $recipe->getPicture();
                $fileName = $this->get('app.fileUploader')->upload($file);
                $recipe->setPicture($fileName);
            } else {
                $recipe->setPicture('available.jpg');
            }

            $em = $this->getDoctrine()->getManager();
            $recipe->setIngredient($arrIng);
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('recipe_show', array('id' => $recipe->getId()));
        }

        return $this->render('recipe/new.html.twig', array(
            'recipe' => $recipe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a recipe entity.
     *
     * @Route("/{id}", name="recipe_show")
     * @Method("GET")
     */
    public function showAction(Recipe $recipe)
    {
        $deleteForm = $this->createDeleteForm($recipe);

        return $this->render('recipe/show.html.twig', array(
            'recipe' => $recipe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing recipe entity.
     *
     * @Route("/{id}/edit", name="recipe_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Recipe $recipe)
    {
        $picture = $recipe->getPicture();
        $deleteForm = $this->createDeleteForm($recipe);
        $editForm = $this->createForm('AppBundle\Form\RecipeType', $recipe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            for ($i = 1; $i <= 5; $i++) {
                $arrIng[] = ($editForm['ingredient' . $i]->getData());
            }
            if ($recipe->getPicture()) {
                ($picture != 'available.jpg') ? unlink('uploads/' . $picture) : null;
                $file = $recipe->getPicture();
                $fileName = $this->get('app.fileUploader')->upload($file);
                $recipe->setPicture($fileName);
            } elseif ($picture) {
                $recipe->setPicture($picture);
            }

            $em = $this->getDoctrine()->getManager();
            $recipe->setIngredient($arrIng);
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('recipe_show', array('id' => $recipe->getId()));
        }

        return $this->render('recipe/edit.html.twig', array(
            'recipe' => $recipe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a recipe entity.
     *
     * @Route("/{id}", name="recipe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Recipe $recipe)
    {
        $form = $this->createDeleteForm($recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            ($recipe->getPicture() != "available.jpg") ? unlink('uploads/' . $recipe->getPicture()) : null;
            $em = $this->getDoctrine()->getManager();
            $em->remove($recipe);
            $em->flush();
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * Creates a form to delete a recipe entity.
     *
     * @param Recipe $recipe The recipe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Recipe $recipe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('recipe_delete', array('id' => $recipe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Search recipe by title.
     *
     * @Route("/search", name="search_title")
     * @method ("POST")
     */
    public function searchTitle(Request $request)
    {
        $query = $request->request->get('search');
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('AppBundle:Recipe')->searchTitle($query);

        return $this->render('recipe/search.html.twig', [
            'query' => $query,
            'recipes' => $result,
        ]);
    }
}
