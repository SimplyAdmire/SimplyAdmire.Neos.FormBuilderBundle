<?php
namespace SimplyAdmire\Neos\FormBuilderBundle\Controller;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

class FormController extends ActionController
{

    /**
     * @Flow\Inject
     * @var \Neos\Form\Persistence\FormPersistenceManagerInterface
     */
    protected $formPersistenceManager;

    /**
     * @deprecated Deprecated as of Neos 1.2.0
     * @return void
     */
    public function indexAction()
    {
        $this->response->setHeader('Content-Type', 'application/json');

        return json_encode($this->formPersistenceManager->listForms());
    }
}
