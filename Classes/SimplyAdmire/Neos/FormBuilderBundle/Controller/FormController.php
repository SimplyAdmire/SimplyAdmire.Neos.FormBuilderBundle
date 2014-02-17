<?php
namespace SimplyAdmire\Neos\FormBuilderBundle\Controller;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;

class FormController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Form\Persistence\FormPersistenceManagerInterface
	 */
	protected $formPersistenceManager;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->response->setHeader('Content-Type', 'application/json');
		return json_encode($this->formPersistenceManager->listForms());
	}

}