<?php 
class grocery_CRUD_extended extends grocery_CRUD
{
    protected $_unique_fields = array();

    public function unique_fields()
    {
        $args = func_get_args();

        if(isset($args[0]) && is_array($args[0]))
        {
            $args = $args[0];
        }

        $this->_unique_fields = $args;

        return $this;
    }

    protected function db_insert_validation()
    {
        $validation_result = (object)array('success'=>false);

        $field_types = $this->get_field_types();
        $unique_fields = $this->_unique_fields;
        $add_fields = $this->get_add_fields();

        if(!empty($unique_fields))
        {
            $form_validation = $this->form_validation();

            foreach($add_fields as $add_field)
            {
                $field_name = $add_field->field_name;
                if(in_array( $field_name, $unique_fields) )
                {
                    $form_validation->set_rules( $field_name, 
                            $field_types[$field_name]->display_as, 
                            'is_unique['.$this->basic_db_table.'.'.$field_name.']');
                }
            }

            if(!$form_validation->run())
            {
                $validation_result->error_message = $form_validation->error_string();
                $validation_result->error_fields = $form_validation->_error_array;

                return $validation_result;
            }
        }
        return parent::db_insert_validation();
    }

    protected function db_update_validation()
    {
        $validation_result = (object)array('success'=>false);

        $field_types = $this->get_field_types();
        $unique_fields = $this->_unique_fields;
        $add_fields = $this->get_add_fields();

        if(!empty($unique_fields))
        {
            $form_validation = $this->form_validation();

            $form_validation_check = false;

            foreach($add_fields as $add_field)
            {
                $field_name = $add_field->field_name;
                if(in_array( $field_name, $unique_fields) )
                {
                    $state_info = $this->getStateInfo();
                    $primary_key = $this->get_primary_key();
                    $field_name_value = $_POST[$field_name];

                    $ci = &get_instance();
                    $previous_field_name_value = 
                        $ci->db->where($primary_key,$state_info->primary_key)
                            ->get($this->basic_db_table)->row()->$field_name;

                    if(!empty($previous_field_name_value) && $previous_field_name_value != $field_name_value) {
                        $form_validation->set_rules( $field_name, 
                                $field_types[$field_name]->display_as, 
                                'is_unique['.$this->basic_db_table.'.'.$field_name.']');

                        $form_validation_check = true;
                    }
                }
            }

            if($form_validation_check && !$form_validation->run())
            {
                $validation_result->error_message = $form_validation->error_string();
                $validation_result->error_fields = $form_validation->_error_array;

                return $validation_result;
            }
        }
        return parent::db_update_validation();
    }


}