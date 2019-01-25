<?php $this->load->view('template/admin/admin-header', $grocery_output); ?>
<?php echo $this->session->flashdata('message'); ?>
<?php echo $grocery_output->output; ?>
<?php $this->load->view('template/admin/admin-footer', $grocery_output); ?>