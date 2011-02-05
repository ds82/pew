<?php
require_once('_entity.php');
require_once('_iFormLayout.php');
abstract class _formEntity extends _entity implements _iFormLayout {

	protected $isNewEntity = false;

	protected $formFields = array();
	protected $hasFormGroups = false;

	protected $validForm = true;
	protected $validationResult = array();

	private $formFieldsWithoutGroups = array();

	public function hasFormErrors() {

		return $this->validForm;
	}
	public function getFormError($field) {

		// debug
		if (is_array($this->validationResult[$field])) $res = implode(', ', $this->validationResult[$field]);
		Registry::getInstance()->log(get_class($this).'->getFormError('.$field.') called. Result is: '.$res);

		return $this->validationResult[$field];
	}

	public function validateForm($data) {

		// FIXME workaround
		//return $data;

		foreach($this->getFormElements(null) AS $f) {

			// first parameter is the form object, you don't need one for validation
			$f->setModel(null, $this);
			$validator = $f->getValidator();
			if (!is_array($validator)) continue;
			foreach($validator AS $v) {
				$msg = '';

				// TODO FIXME as soon as using php >= 5.3.0
				// php 5.3
				$valid = $v::validate($data[$f->field()], $data[$f->field()], true, $msg);
				// php 5.2
				//$valid = call_user_func($v.'::validate', $data[$f->field()], &$data[$f->field()], true, &$msg);

				if ( ! $valid) {

					$this->validForm = false;
					$this->validationResult[$f->field()][] = $msg;
					// let pew return a statusOk = false
					pew()->setStatusFailed();
				}
			}
		}
		return $data;
	}

	public function setFormValues($data) {

		foreach($data AS $i => $d) {

			$f = $this->getFormElement($i);
			// skip alle non form elements
			if ( ! $f instanceof _formInput) continue;
			// first parameter is the form object, you don't need one for setters
			$f->setModel(null, $this);

			// try/catch until models are correct
			try {
				$f->set($d);
			} catch (Exception $e) {

			}
		}
	}

	public function getFormGroups() {
		if ($this->hasFormGroups) return array_keys($this->formFields);
		else return array(0 => null);
	}
	public function groupHeader($group) {
		return;
	}
	public function groupFooter($group) {
		return;
	}

	public function setFormModel($_collection) {}

	public function getFormElements($group) {

		if ($group == null) {

			$arr = array();
			if (is_array($this->formFields)) {
				foreach($this->formFields AS $g) {
					if (is_array($g))
						$arr = array_merge($arr, $g);
					else
						$arr[] = $g;
				}
			}
			return $arr;
		}
		else return $this->formFields[$group];
	}

	public function getFormElement($element) {

		if (count($this->formFieldsWithoutGroups) > 0)
			return $this->formFieldsWithoutGroups[$element];

		if ($this->hasFormGroups) {

			$arr = array();
			foreach($this->formFields AS $a) {
				$arr = array_merge($arr, $a);
			}
			$this->formFieldsWithoutGroups = $arr;
			return $arr[$element];
		} else {
			$this->formFields[$element];
		}

	}
}

?>