<?php
namespace pew\widgets;
use pew;
use pew\html;

abstract class AbstractWidget extends html\AbstractHtml {

	protected $name;
    protected $id;
    protected $boxName = "";
    protected $model;
    protected $getCb = null;
    protected $setCb = null;
    protected $validator;
    protected $parent;
    protected $cssClasses = array();
    protected $appendedWidgets = array();

	private $label = null;

	private $options;

	private $children = array();

	protected $injector;
	protected $dispatcher;
	protected $context;

	public function __construct(pew\Injector $injector, pew\Dispatcher $dispatcher, pew\Context $context) {
		$this->injector = $injector;
		$this->dispatcher = $dispatcher;
		$this->context = $context;
	}

	public function setOptions($name, $validator, $options = array(), $getCb = null, $setCb = null) {

		$this->name = $name;
		$this->getCb = $getCb;
		$this->setCb = $setCb;
		$this->validator = $validator;
		$this->options = $options;
	}

	public function setId($id) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}

	public function setModel($m) {
		$this->model = $m;
	}

	public function setLabel($l) {
		$this->label = $l;
	}
	public function setText($t) {
		$this->label = $t;
	}

	public function getLabel() {
		$label = ($this->label != null) ? $this->label : _('Webgui.form.' . $this->getCleanName());
		return $label;
	}

	public function setParent($p) {
		$this->parent = $p;
	}
	public function getParent() {
		return $this->parent;
	}

	public function addChild(AbstractWidget $w) {

		$this->children[] = $w;
	}
	public function addWidget(AbstractWidget $w) {
		return $this->addChild($w);
	}

	public function removeChild(AbstractWidget $w) {

		$k = array_search($w, $this->children);
		if ($k != false) {
			unset($this->children[$k]);
			return true;
		}
		return false;
	}
	public function getChildren() {
		return $this->children;
	}

    public function get() {
		if ($this->getCb !== null) {
			if (is_string($this->getCb) AND method_exists($this->model, $this->getCb)) return call_user_func(array($this->model, $this->getCb));
            elseif (is_object($this->getCb) && get_class($this->getCb) == 'Closure') {
                $closure = $this->getCb;
                return $closure();
            }
            else return $this->getCb;
		} else {
			$getCb = 'get';
			if (method_exists($this->model, $getCb))
				return call_user_func(array($this->model, $getCb), $this->name);
			else return null;
		}
	}

	public function set($v) {

	}

	public function getName() {
		if ($this->boxName != '') return $this->boxName.'['.$this->name.']';
		else return $this->name;
	}

	public function getCleanName() {
		return $this->name;
	}

	public function setBoxName($n) {
		$this->boxName = $n;
	}

	public function addClass($class) {

       if (is_array($class)) $this->cssClasses = array_merge($this->cssClasses, $class);
       else $this->cssClasses[] = $class;
	}
    public function hasCSS() {
        return (count($this->cssClasses) > 0);
    }
	public function getClassAsString() {
		return implode(' ', array_unique($this->cssClasses));
	}

	abstract public function render($model = null);

	public function preRender() {

		echo '<li>';
	}
	public function postRender() {

		echo '</li>';
	}

    public function addAppendedWidget(AbstractWidget $widget) {
        $this->appendedWidgets[] = $widget;
    }

    public function renderAppendedWidgets() {
        if (count($this->appendedWidgets) > 0) {
            foreach($this->appendedWidgets AS $widget)
                $widget->render();
        }
    }

}
?>