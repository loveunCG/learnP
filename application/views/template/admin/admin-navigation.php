<ul class="sidebar-menu">

	<li <?php if(isset($activemenu) && $activemenu == 'dashboard') echo ' class="active"';?>>
		<a href='<?php echo URL_ADMIN_INDEX;?>'>
		<i class='fa fa-home'></i> <?php echo get_languageword('Home');?></a>
		</li>			

		
		<li class='treeview <?php if(isset($activemenu) && $activemenu == 'bookings') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-calendar-check-o'></i> <?php echo get_languageword('Bookings')?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'student_bookings') echo ' class="active"';?>>
			<a href='<?php echo URL_ADMIN_STUDENT_BOOKINGS;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('student Bookings');?></a>
			</li>
			
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'inst_batches') echo ' class="active"';?>>
			<a href='<?php echo URL_ADMIN_INST_BATCHES;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('institute_batches');?></a>
			</li>
			
		</ul>
		</li>



		<li class='treeview <?php if(isset($activemenu) && $activemenu == 'users') echo 'active';?>'>
		<a href='#'>
			<i class='fa fa-users'></i> <?php echo get_languageword('Users')?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'view_users') echo ' class="active"';?>>
			<a href='<?php echo URL_AUTH_INDEX;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('view_users');?></a>
			</li>
			
			<li <?php if(isset($activesubmenu) && $activesubmenu == '2') echo ' class="active"';?>>
			<a href='<?php echo URL_AUTH_INDEX;?>/2'><i class='fa fa-circle-o'></i> <?php echo get_languageword('students');?></a>
			</li>
			
			<li <?php if(isset($activesubmenu) && $activesubmenu == '3') echo ' class="active"';?>>
			<a href='<?php echo URL_AUTH_INDEX;?>/3'><i class='fa fa-circle-o'></i> <?php echo get_languageword('tutors');?></a>
			</li>
			
			<li <?php if(isset($activesubmenu) && $activesubmenu == '4') echo ' class="active"';?>>
			<a href='<?php echo URL_AUTH_INDEX;?>/4'><i class='fa fa-circle-o'></i> <?php echo get_languageword('institutes');?></a>
			</li>
			
			<li  <?php if(isset($activesubmenu) && $activesubmenu == 'add') echo ' class="active"';?>>
			<a href='<?php echo URL_AUTH_INDEX;?>/add'><i class='fa fa-circle-o'></i> <?php echo get_languageword('create');?></a>
			</li>
			
			<li  <?php if(isset($activesubmenu) && $activesubmenu == 'import') echo ' class="active"';?>>
			<a href='<?php echo base_url();?>import/index/users'><i class='fa fa-circle-o'></i> <?php echo get_languageword('import');?></a>
			</li>
		</ul>
		</li>



		<li class='treeview <?php if(isset($activemenu) && $activemenu == 'myleads') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-user-md'></i> <?php echo get_languageword('leads');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'all_Leads') echo 'class="active"';?>>
			<a href='<?php echo URL_ADMIN_ALL_LEADS;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('all_leads');?></a>
			</li>			
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'opened_Leads') echo 'class="active"';?>>
			<a href='<?php echo URL_ADMIN_OPENED_LEADS;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('open_leads');?></a>
			</li>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'closed_Leads') echo 'class="active"';?>>
			<a href='<?php echo URL_ADMIN_CLOSED_LEADS;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('closed_leads');?></a>
			</li>
			
		</ul>
	</li>


	<!--Tutor Selling Courses Start-->
	<li <?php if(isset($activemenu) && $activemenu == 'tutor_selling_courses') echo ' class="active"';?>>
		<a href="<?php echo URL_TUTOR_LIST_SELLING_COURSES;?>">
			<i class="fa fa-book"></i><?php echo get_languageword('Tutor_Selling_Courses');?></a>
	</li>
	<!--Tutor Selling Courses End-->


	<li <?php if(isset($activemenu) && $activemenu == 'purchased_courses') echo ' class="active"';?>>
			<a href="<?php echo URL_ADMIN_VIEW_PURCHASED_COURSES;?>">
				<i class="fa fa-money"></i><?php echo get_languageword('View_Purchased_Courses');?></a>
		</li>



	<!--Money Conversion From Tutor-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'tutor_money_reqs') echo 'active';?>'>
		<a href='#'>
			<i class='fa fa-money'></i> <?php echo get_languageword('tutor_money_requests');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'tutor_Pending') echo ' class="active"';?>>
			<a href='<?php echo URL_ADMIN_TUTOR_MONEY_CONVERSION_REQUESTS."/Pending";?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('pending');?></a>
			</li>			
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'tutor_Done') echo ' class="active"';?>>
			<a href='<?php echo URL_ADMIN_TUTOR_MONEY_CONVERSION_REQUESTS."/Done";?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('completed');?></a>
			</li>
		</ul>
	</li>

	<!--Money Conversion From Institute-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'inst_money_reqs') echo 'active';?>'>
		<a href='#'>
			<i class='fa fa-money'></i> <?php echo get_languageword('institute_money_requests');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'inst_Pending') echo ' class="active"';?>>
			<a href='<?php echo URL_ADMIN_INST_MONEY_CONVERSION_REQUESTS."/Pending";?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('pending');?></a>
			</li>			
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'inst_Done') echo ' class="active"';?>>
			<a href='<?php echo URL_ADMIN_INST_MONEY_CONVERSION_REQUESTS."/Done";?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('completed');?></a>
			</li>
		</ul>
	</li>
	
	
	<!--Catgories Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'categories') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-cog'></i> <?php echo get_languageword('categories');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'categories') echo ' class="active"';?>>
				<a href='<?php echo URL_CATEGORIES_INDEX;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('list_categories');?></a>
			</li>					
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'categories-add') echo ' class="active"';?>>
				<a href='<?php echo URL_CATEGORIES_INDEX;?>/add'><i class='fa fa-circle-o'></i> <?php echo get_languageword('add_category');?></a>
			</li>
			
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'courses') echo ' class="active"';?>>
				<a href='<?php echo URL_CATEGORIES_COURSES;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('list_courses');?></a>
			</li>					
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'courses-add') echo ' class="active"';?>>
				<a href='<?php echo URL_CATEGORIES_COURSES;?>/add'><i class='fa fa-circle-o'></i> <?php echo get_languageword('add_course');?></a>
			</li>
		</ul>
	</li>
	<!--Categories End-->
	

	<!--Options Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'options') echo 'active';?>'>
		<a href='#'>
		<i class='fa fa-cogs'></i> <?php echo get_languageword('options');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'degree') echo ' class="active"';?>>
				<a href='<?php echo URL_OPTIONS_INDEX;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('list_degrees');?></a>
			</li>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'add_degree') echo ' class="active"';?>>
				<a href='<?php echo URL_OPTIONS_INDEX;?>/add'><i class='fa fa-circle-o'></i> <?php echo get_languageword('add_degree');?></a>
			</li>
		</ul>
	</li>
	<!--Locations End-->
	
	<!--Locations Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'locations') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-map-marker'></i> <?php echo get_languageword('locations');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'locations') echo ' class="active"';?>>
				<a href='<?php echo URL_LOCATIONS_INDEX;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('list_locations');?></a>
			</li>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'locations-add') echo ' class="active"';?>>
				<a href='<?php echo URL_LOCATIONS_INDEX;?>/add'><i class='fa fa-circle-o'></i> <?php echo get_languageword('add_location');?></a>
			</li>
		</ul>
	</li>
	<!--Locations End-->
	
	<!--Packages Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'packages') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-archive'></i> <?php echo get_languageword('packages');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'list_packages') echo ' class="active"';?>>
				<a href='<?php echo URL_PACKAGE_INDEX;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('list_packages');?></a>
			</li>					
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'add_package') echo ' class="active"';?>>
				<a href='<?php echo URL_PACKAGE_INDEX;?>/add'><i class='fa fa-circle-o'></i> <?php echo get_languageword('add_package');?></a>
			</li>
		</ul>
	</li>
	<!--Packages End-->
	
	
	<!--Certificates Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'certificates') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-shield'></i> <?php echo get_languageword('certificates');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'certificates') echo ' class="active"';?>>
				<a href='<?php echo base_url();?>certificates/index'><i class='fa fa-circle-o'></i> <?php echo get_languageword('list_certificates');?></a>
			</li>					
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'certificates-add') echo ' class="active"';?>>
				<a href='<?php echo base_url();?>certificates/index/add'><i class='fa fa-circle-o'></i> <?php echo get_languageword('add_certificate');?></a>
			</li>
		</ul>
	</li>
	<!--Certificates End-->
	
	
	<!--Pages Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'pages') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-file'></i> <?php echo get_languageword('pages');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			
			<li<?php if(isset($activesubmenu) && $activesubmenu == 'dynamic_pages') echo ' class="active"';?>>
				<a href='<?php echo URL_ADMIN_DYNAMIC_PAGES; ?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('dynamic_pages');?></a>
			</li>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'faqs') echo ' class="active"';?>>
				<a href='<?php echo URL_ADMIN_FAQS; ?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('FAQs');?></a>
			</li>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'scroll_news') echo ' class="active"';?>>
				<a href='<?php echo URL_ADMIN_SCROLL_NEWS; ?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('scroll_News');?></a>
			</li>
		</ul>
	</li>
	<!--Pages End-->


	<!--Sections Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'sections') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-cog'></i> <?php echo get_languageword('sections');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>

			<li <?php if(isset($activesubmenu) && $activesubmenu == 'list_sections') echo ' class="active"';?>>
				<a href='<?php echo URL_SECTIONS_INDEX;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('list_sections');?></a>
			</li>
		</ul>
	</li>
	<!--Sections End-->


	<!--Settings Start-->
	<li <?php if(isset($activemenu) && $activemenu == 'settings') echo ' class="active"';?>>
		<a href="<?php echo URL_SETTINGS_INDEX;?>">
			<i class="fa fa-wrench"></i><?php echo get_languageword('settings');?></a>
	</li>
	<!--Settings End-->
	
	<!--Email Templates Start-->
	<li <?php if(isset($activemenu) && $activemenu == 'etemplates') echo ' class="active"';?>>
		<a href="<?php echo base_url();?>etemplates/index">
			<i class="fa fa-envelope"></i><?php echo get_languageword('email_templates');?></a>
	</li>
	<!--Email Templates End-->
	
	<!--Site Testimonials Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'sitetestimonials') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-comments-o'></i> <?php echo get_languageword('site_Testimonials');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'sitetestimonials') echo ' class="active"';?>>
				<a href='<?php echo base_url();?>sitetestimonials/index'><i class='fa fa-circle-o'></i> <?php echo get_languageword('list_testimonials');?></a>
			</li>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'sitetestimonials-add') echo ' class="active"';?>>
				<a href='<?php echo base_url();?>/sitetestimonials/index/add'><i class='fa fa-circle-o'></i> <?php echo get_languageword('add_testimonial');?></a>
			</li>
		</ul>
	</li>
	<!--Site Testimonials End-->
	
	<!--Team Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'team') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-users'></i> <?php echo get_languageword('Team');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'team') echo ' class="active"';?>>
				<a href='<?php echo base_url();?>team/index'><i class='fa fa-circle-o'></i> <?php echo get_languageword('list_team');?></a>
			</li>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'team-add') echo ' class="active"';?>>
				<a href='<?php echo base_url();?>/team/index/add'><i class='fa fa-circle-o'></i> <?php echo get_languageword('add_team');?></a>
			</li>
		</ul>
	</li>
	<!--Team End-->
				
	<!--Languages Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'language') echo 'active';?>'>
		<a href='#'>
            <i class='fa fa-language'></i> <?php echo get_languageword('language_settings');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'view') echo 'class="active"';?>>
				<a href='<?php echo URL_LANGUAGE_INDEX;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('view_languages');?></a>
			</li>					
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'add') echo 'class="active"';?>>
				<a href='<?php echo URL_LANGUAGE_ADDLANGUEGE;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('add_language');?></a>
			</li>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'phrases') echo 'class="active"';?>>
				<a href='<?php echo URL_LANGUAGE_PHRASES;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('view_phrases');?></a>
			</li>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'languages') echo 'class="active"';?>>
				<a href='<?php echo URL_LANGUAGE_LANGUAGES;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('view_tutor_languages');?></a>
			</li>
		</ul>
	</li>
	<!--Languages End-->
	
	<!--Payments Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'payments') echo 'active';?>'>
	<a href='#'>
        <i class='fa fa-file-text'></i> <?php echo get_languageword('payments');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu =='payments') echo 'class="active"';?>>
				<a href='<?php echo URL_ADMIN_PAYMENTS;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('payments');?></a>
			</li>					
			
			<li <?php if(isset($activesubmenu) && $activesubmenu =='pending') echo 'class="active"';?>>
				<a href='<?php echo URL_ADMIN_PAYMENTS;?>/pending'><i class='fa fa-circle-o'></i> <?php echo get_languageword('pending payments');?></a>
			</li>
		</ul>
	</li>
	<!--Payments End-->
	
	<!--Reports Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'reports') echo 'active';?>'>
	<a href='#'>
        <i class='fa fa-file-text'></i> <?php echo get_languageword('reports');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu =='users_info') echo 'class="active"';?>>
				<a href='<?php echo URL_ADMIN_USERS_INFO;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('users');?></a>
			</li>					
			
			<li <?php if(isset($activesubmenu) && $activesubmenu =='packages_info') echo 'class="active"';?>>
				<a href='<?php echo URL_ADMIN_PACKAGAES;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('packages');?></a>
			</li>
		</ul>
	</li>
	<!--Reports End-->
			
	<!--Profile Start-->
	<li class='treeview <?php if(isset($activemenu) && $activemenu == 'profile') echo 'active';?>'>
		<a href='#'>
		<i class='fa fa-user'></i> <?php echo get_languageword('profile');?> <i class='fa fa-angle-left pull-right'></i>
		</a>
		<ul class='treeview-menu'>
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'my_profile') echo ' class="active"';?>>
				<a href='<?php echo URL_AUTH_PROFILE;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('my_profile');?></a>
			</li>					
			<li <?php if(isset($activesubmenu) && $activesubmenu == 'change_password') echo ' class="active"';?>>
				<a href='<?php echo URL_AUTH_CHANGE_PASSWORD;?>'><i class='fa fa-circle-o'></i> <?php echo get_languageword('change_password');?></a>
			</li>
		</ul>
	</li>
	<!--Profile End-->	

		
	<li class=''>
	<a href='<?php echo URL_AUTH_LOGOUT;?>'>
	<i class='fa fa-sign-out'></i><?php echo get_languageword('Sign Out');?></a>
	</li>						
</ul>