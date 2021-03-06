<?php

class Button extends Control {

    public function __toString() {
        $label = $this->getPropertyValue('label', $this->identifier);
        $id = $this->getId();
        $class = $this->getPropertyValue('class', 'plainbtn');

        return tag('input', array(
            'type' => $this->getPropertyValue('type', 'button'),
            'class' => $class,
            'id' => $id,
            'name' => $this->getPropertyValue('name', $id),
            'onmouseover' => "this.className='{$class} {$class}hov'",
            'onmouseout' => "this.className='{$class}'",
            'value' => $label,
            )
        );
    }

    public function getId() {
        $label = $this->getPropertyValue('label', $this->identifier);
        return $this->getPropertyValue('id', 'btn' . ucfirst($label));
    }

}
