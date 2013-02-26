<?php

/**
 * Make a select field with a group option.
 * @example http://www.w3schools.com/tags/tag_optgroup.asp
 *
 * create table opts ( id int auto_numeric primary key, name varchar(50) not null, parent_id int not null );
 *
 * insert into opts(name,parent_id) values ('parent 1',0);
 * insert into opts(name,parent_id) values ('parent 2',0);
 * insert into opts(name,parent_id) values ('child 1',1);
 * insert into opts(name,parent_id) values ('child 2',1);
 * insert into opts(name,parent_id) values ('child 3',2);
 * insert into opts(name,parent_id) values ('child 4',2);
 *
 * $this->build("OptionGroup","optGroup")
                ->setLabel("The Label")
                ->setSource($this->src_p4a_db_source) // a p4a_db_source
                ->setSourceDescriptionField('field_description')
                ->setSourceValueField('field_value')
                ->setSourceParentField("field_parent") // this is the parent id field
                ->setValue($this->fields->id_sectorinversion->getValue()); // set the value of the field
   To save the field value rewrite the saveRow() function and add the following line:
 *
 * $this->fields->your_field->setValue($this->sector->getNewValue());
 *
 */
class OptionGroup extends P4A_Field {

    /**
     * The name of the data field for make a parent child relation.
     * @var string
     */
    public $parent_field = null;

    /**
     * @param string $name Mnemonic identifier for the object.
     *
     */
    public function __construct($name) {
        parent::__construct($name);
        return $this;
    }

    /**
     * Sets the parent field
     *
     * @param string $name
     * @return P4A_Field
     */
    public function setSourceParentField($name = null) {
        $this->parent_field = $name;
        return $this;
    }

    /**
     * Get the parent field
     *
     * @return string
     */
    public function getSourceParentField() {
        return $this->parent_field;
    }

    /**
     * Returns the HTML rendered field.
     * @return string
     */
    public function getAsString() {
        $id = $this->getId();

        $header = "<select id='{$id}input' ";
        $close_header = '>';
        $footer = '</select>';
        $header .= $this->composeStringActions() . $this->composeStringProperties();

        if (!$this->isEnabled()) {
            $header .= 'disabled="disabled" ';
        }

        $header .= $close_header;
        $external_data = $this->data->getAll();
        $value_field = $this->getSourceValueField();
        $description_field = $this->getSourceDescriptionField();
        $parent_field = $this->getSourceParentField();
        $new_value = $this->getNewValue();

        if(is_null($parent_field)){
            trigger_error("parent_field must be set <br />ex: setSourceParentField(\"field_name\")", E_USER_ERROR);
        }
        if ($this->isNullAllowed()) {
            if ($this->null_message === null) {
                $message = 'None Selected';
            } else {
                $message = $this->null_message;
            }

            $header .= "<option value=''>" . __($message) . "</option>";
        }
        foreach ($external_data as $key => $current) {

            if ($current[$parent_field] == 0) {
                $sContent .= "<optgroup label=\"" . htmlspecialchars($current[$description_field]) . "\">\n";
                next;
            }
            foreach ($external_data as $key2 => $current2) {
                if ($current2[$parent_field] == $current[$value_field]) {

                    if ($current2[$value_field] == $new_value) {
                        $selected = "selected='selected' style=\"background-color:#E2E7ED;font-weight:bold\"";
                    } else {
                        $selected = "";
                    }
                    $sContent .= "<option $selected value=\"" . $current2[$value_field] . "\">";
                    if ($this->isFormatted()) {
                        $sContent .= htmlspecialchars($this->format($current2[$description_field], $this->data->fields->$description_field->getType(), $this->data->fields->$description_field->getNumOfDecimals()));
                    } else {
                        $sContent .= htmlspecialchars($current2[$description_field]);
                    }
                    $sContent .= "</option>\n";
                }
            }
        }
        $header .= $sContent;

        return $this->composeLabel() . $header . $footer;
    }
}
