<?php

declare(strict_types = 1);

use Inpsyde\Lib\WpEngine\Template;

Template::render('Plugin:Inpsyde/Templates/Layout/default/top.php');

?>

<div id="userList" class="user-list">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2><?= esc_html(__('User List'));?></h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" width="3%">#</th>
                                    <th scope="col"><?= esc_html(__('Name'));?></th>
                                    <th scope="col"><?= esc_html(__('Username'));?></th>
                                    <th scope="col"><?= esc_html(__('Email'));?></th>
                                    <th scope="col"><?= esc_html(__('Address'));?></th>
                                    <th scope="col"><?= esc_html(__('Phone'));?></th>
                                    <th scope="col"><?= esc_html(__('Website'));?></th>
                                    <th scope="col"><?= esc_html(__('Company'));?></th>
                                    <th scope="col"><?= esc_html(__('Actions'));?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row" class="text-center" colspan="9"><?= esc_html(__('Loading, please wait...'));?></td>
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
                            <h7><?= esc_html(__('Company'));?> : <span data-model="company"></span></h7><br>
                            <h7><?= esc_html(__('Website'));?> : <span data-model="website"></span></h7>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a 
                                        class="nav-link active" 
                                        id="basicInfo-tab" 
                                        data-toggle="tab" 
                                        href="#basicInfo" 
                                        role="tab" 
                                        aria-controls="basicInfo" 
                                        aria-selected="true">
                                            <?= esc_html(__('User Info'));?>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content ml-1" id="myTabContent">
                                <div 
                                    class="tab-pane fade show active" 
                                    id="basicInfo" 
                                    role="tabpanel" 
                                    aria-labelledby="basicInfo-tab"
                                >
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label><?= esc_html(__('Full Name'));?></label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <span data-model="name"></span>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label><?= esc_html(__('Username'));?></label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <span data-model="username"></span>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label><?= esc_html(__('Phone'));?></label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <span data-model="phone"></span>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label><?= esc_html(__('Email'));?></label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <span data-model="email"></span>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label><?= esc_html(__('Address'));?></label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <span data-model="address"></span>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary"><?= esc_html(__('Go Back To The List'));?></button>
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

<?php

Template::render('Plugin:Inpsyde/Templates/Layout/default/bottom.php');