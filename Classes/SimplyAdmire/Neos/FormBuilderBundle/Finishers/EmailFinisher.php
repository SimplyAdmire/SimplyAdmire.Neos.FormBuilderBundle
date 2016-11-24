<?php
namespace SimplyAdmire\Neos\FormBuilderBundle\Finishers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Form".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * @author support@simplyadmire.com
 */
class EmailFinisher extends \TYPO3\Form\Finishers\EmailFinisher {

	/**
	 * Extends the functionality of the default parseOption() method
	 * by resolving values starting with fieldIdentifier: in the posted
	 * form (if the value is available).
	 *
	 * @param string $optionName
	 * @return mixed|string
	 */
	protected function parseOption($optionName) {
		$value = parent::parseOption($optionName);

		if (substr($value, 0, 16) === 'fieldIdentifier:') {
			$formRuntime = $this->finisherContext->getFormRuntime();
			$field = str_replace('fieldIdentifier:', '', $value);
			if ($formRuntime->getFormState()->getFormValue($field)) {
				return $formRuntime->getFormState()->getFormValue($field);
			}
		}

		return $value;
	}
}
