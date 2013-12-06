P4A Option Group
==============
Example of this widget:

<img src="http://daniel.carrero.cl/images/P4AOptionGroup.jpg" />


Use
==============
        $this->build("P4A_OptionGroup_Field", "option_group")
                ->setLabel("Label for your Option Group")
                ->setSource($this->p4a_source)
                ->setSourceDescriptionField('field_description')
                ->setSourceValueField('id_field')
                ->setSourceParentField("parent_id")
                ->setValue($this->fields->id_field->getValue())
                ->setStyleProperty("width", "220px")
                ->label->setWidth(120);
                
saveRow() function:

    public function saveRow()
    {
        $this->fields->your_field->setValue($this->option_group->getNewValue());
        parent::saveRow();
        /***/
    }
