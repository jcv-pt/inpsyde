<?php

use Inpsyde\Lib\WpEngine\Template;

Template::start();

?>

<div id="userList" class="user-list">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h2><?= __('User List');?></h2>
					<div class="table-responsive">
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th scope="col" width="3%">#</th>
									<th scope="col"><?= __('Name');?></th>
									<th scope="col"><?= __('Username');?></th>
									<th scope="col"><?= __('Email');?></th>
									<th scope="col"><?= __('Address');?></th>
									<th scope="col"><?= __('Phone');?></th>
									<th scope="col"><?= __('Website');?></th>
									<th scope="col"><?= __('Company');?></th>
									<th scope="col"><?= __('Actions');?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td scope="row" class="text-center" colspan="9"><?= __('Loading, please wait...');?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="userDetails" class="user-details">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="row mb-4">
						<div class="col col-md-2">
							<img src="http://placehold.it/150x150" class="img-thumbnail" />
						</div>
						<div class="col-md-auto">
							<h2><span data-model="name"></span></h2>
							<h7><?= __('Company');?> : <span data-model="company"></span></h7><br>
							<h7><?= __('Website');?> : <span data-model="website"></span></h7>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="basicInfo-tab" data-toggle="tab" href="#basicInfo" role="tab" aria-controls="basicInfo" aria-selected="true"><?= __('User Info');?></a>
								</li>
							</ul>
							<div class="tab-content ml-1" id="myTabContent">
								<div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicInfo-tab">
									<div class="row">
										<div class="col-sm-3 col-md-2 col-5">
											<label><?= __('Full Name');?></label>
										</div>
										<div class="col-md-8 col-6">
											<span data-model="name"></span>
										</div>
									</div>
									<hr />
									<div class="row">
										<div class="col-sm-3 col-md-2 col-5">
											<label><?= __('Username');?></label>
										</div>
										<div class="col-md-8 col-6">
											<span data-model="username"></span>
										</div>
									</div>
									<hr />
									<div class="row">
										<div class="col-sm-3 col-md-2 col-5">
											<label><?= __('Phone');?></label>
										</div>
										<div class="col-md-8 col-6">
											<span data-model="phone"></span>
										</div>
									</div>
									<hr />
									<div class="row">
										<div class="col-sm-3 col-md-2 col-5">
											<label><?= __('Email');?></label>
										</div>
										<div class="col-md-8 col-6">
											<span data-model="email"></span>
										</div>
									</div>
									<hr />
									<div class="row">
										<div class="col-sm-3 col-md-2 col-5">
											<label><?= __('Address');?></label>
										</div>
										<div class="col-md-8 col-6">
											<span data-model="address"></span>
										</div>
									</div>
									<hr />
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-default"><?= __('Go Back To The List');?></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="preloader">
  <div id="loader"></div>
</div>

<?= Template::extend('Plugin:Inpsyde/Templates/Layout/default.php');?>