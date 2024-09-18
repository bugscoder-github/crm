<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { usePage, Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const navClass = (role_name) => {
  return "main-header navbar navbar-expand navbar-white navbar-light role-"+role_name.toLowerCase();
}

const navMenuOpenClass = (hasPage) => {
  let className = "nav-item";
  if (hasPage) { className += " menu-open"; }

  return className;
}

const navActiveClass = (hasPage) => {
  let className = "nav-link";
  if (hasPage) { className += " active"; }

  return className;
};

const page = usePage();
const leadCount = ref(page.props.notifications ?? 0);

const eventSource = new EventSource('/_backend/notifications');
eventSource.onmessage = function(event) {
    let data = JSON.parse(event.data);
    console.log(data.count, " ", leadCount.value);
    if (data.count > leadCount.value) {
      leadCount.value = data.count;
      
      $(document).Toasts('create', {
        class: 'bg-success',
        title: 'New Lead',
        subtitle: '',
        body: 'New Lead Alert. <a href="/_backend/lead">Lead</a>'
      })
    }
};
</script>

<template>
    <div class="wrapper">
		<!-- <div class="preloader flex-column justify-content-center align-items-center">
			<img class="animation__shake" src="/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
		</div> -->

        <nav :class="navClass($page.props.auth.user.roles[0].name)">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a :href="route('contactus.create')" class="nav-link" target="_blank">Contact Us</a>
      </li><li class="nav-item d-none d-sm-inline-block">
        <a href="/changelog" class="nav-link" target="_blank">Changelog</a>
      </li>
        <!-- <li><button type="button" class="btn btn-success toastsDefaultSuccess">
                  Launch Success Toast
                </button></li> -->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> -->

      <!-- Messages Dropdown Menu -->
      <!-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <div class="media">
              <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li> -->
      <!-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li> -->
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li> -->
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
      <li class="nav-item d-none d-sm-inline-block">
        <div class="nav-link" style="color:#000">Branch: {{ me($page)['teams']['name'] }}</div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
			{{ $page.props.auth.user.name }}&nbsp;
      <div v-if="$page.props.auth.user.roles[0].name" :class="getRoleClass($page.props.auth.user.roles[0].name)">{{ $page.props.auth.user.roles[0].name }}</div>
      <i class="fas fa-angle-down right ml-2"></i>
		</a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <Link :href="route('logout')" method="post" as="button" class="dropdown-item">Log Out</Link>
          <div class="dropdown-divider"></div>
          <span class="dropdown-item">
          		<!-- <Link :href="route('profile.edit')">Profile</Link> -->
          		<Link :href="route('user.edit', $page.props.auth.user.id)">Profile</Link>
          </span>
        </div>
      </li>
    </ul>
  </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">UniSmart CRM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        	<li class="nav-item">
            <a :href="route('dashboard')" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li :class="navMenuOpenClass(route().current('lead.index') || route().current('lead.create') || route().current('lead.edit'))">
                <a href="#" class="nav-link ">
                	<i class="nav-icon fas fa-comment"></i>
                    <p>
                    	Lead
                		<i class="fas fa-angle-left right"></i>
                	</p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('lead.index')" :class="navActiveClass(route().current('lead.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                             <span class="badge badge-info right">{{ leadCount }}</span>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('lead.create')" :class="navActiveClass(route().current('lead.create') || route().current('lead.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
            <li :class="navMenuOpenClass(route().current('quotation.index') || route().current('quotation.create') || route().current('quotation.edit'))">
                <a href="#" class="nav-link "> <i class="nav-icon fas fa-address-card"></i>
                    <p>Quotation
                		<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('quotation.index')" :class="navActiveClass(route().current('quotation.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('quotation.create')" :class="navActiveClass(route().current('quotation.create') || route().current('quotation.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
            <li :class="navMenuOpenClass(route().current('invoice.index') || route().current('invoice.create') || route().current('invoice.edit'))">
                <a href="#" class="nav-link "> <i class="nav-icon fas fa-address-card"></i>
                    <p>Invoice
                		<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('invoice.index')" :class="navActiveClass(route().current('invoice.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('invoice.create')" :class="navActiveClass(route().current('invoice.create') || route().current('invoice.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
          	<li :class="navMenuOpenClass(route().current('user.index') || route().current('user.create') || route().current('user.edit'))" v-if="isAdmin($page) || isOwner($page)">
                <a href="#" class="nav-link "> <i class="nav-icon fas fa-user"></i>
                    <p>Users
                		<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('user.index')" :class="navActiveClass(route().current('user.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('user.create')" :class="navActiveClass(route().current('user.create') || route().current('user.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
            <li :class="navMenuOpenClass(route().current('team.index') || route().current('team.create') || route().current('team.edit'))" v-if="isOwner($page)">
                <a href="#" class="nav-link "> <i class="nav-icon fas fa-user"></i>
                    <p>Team
                		<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('team.index')"  :class="navActiveClass(route().current('team.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('team.create')" :class="navActiveClass(route().current('team.create') || route().current('team.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
            <li :class="navMenuOpenClass(route().current('customer.index') || route().current('customer.create') || route().current('customer.edit'))">
                <a href="#" class="nav-link "> <i class="nav-icon fas fa-address-card"></i>
                    <p>Customer
                		<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('customer.index')"  :class="navActiveClass(route().current('customer.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('customer.create')" :class="navActiveClass(route().current('customer.create') || route().current('customer.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
            <li class="nav-header">Product/Service</li>
            <li :class="navMenuOpenClass(route().current('category.index') || route().current('category.create') || route().current('category.edit'))">
                <a href="#" class="nav-link "> <i class="nav-icon fas fa-address-card"></i>
                    <p>Category
                		<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('category.index')"  :class="navActiveClass(route().current('category.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('category.create')" :class="navActiveClass(route().current('category.create') || route().current('category.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
            <li :class="navMenuOpenClass(route().current('supplier.index') || route().current('supplier.create') || route().current('supplier.edit'))">
                <a href="#" class="nav-link "> <i class="nav-icon fas fa-address-card"></i>
                    <p>Supplier
                		<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('supplier.index')"  :class="navActiveClass(route().current('supplier.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('supplier.create')" :class="navActiveClass(route().current('supplier.create') || route().current('supplier.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
            <li :class="navMenuOpenClass(route().current('location.index') || route().current('location.create') || route().current('location.edit'))">
                <a href="#" class="nav-link "> <i class="nav-icon fas fa-address-card"></i>
                    <p>Location
                		<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('location.index')"  :class="navActiveClass(route().current('location.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('location.create')" :class="navActiveClass(route().current('location.create') || route().current('location.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
            <li :class="navMenuOpenClass(route().current('productService.index') || route().current('productService.create') || route().current('productService.edit'))">
                <a href="#" class="nav-link "> <i class="nav-icon fas fa-address-card"></i>
                    <p>Product/Service
                		<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('productService.index')"  :class="navActiveClass(route().current('productService.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('productService.create')" :class="navActiveClass(route().current('productService.create') || route().current('productService.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
            <li :class="navMenuOpenClass(route().current('productServiceInOut.index') || route().current('productServiceInOut.create') || route().current('productServiceInOut.edit'))">
                <a href="#" class="nav-link "> <i class="nav-icon fas fa-address-card"></i>
                    <p>Product/Service In/Out
                		<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <Link :href="route('productServiceInOut.index')"  :class="navActiveClass(route().current('productServiceInOut.index'))">
                             <i class="nav-icon fas"></i><p>List</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('productServiceInOut.create')" :class="navActiveClass(route().current('productServiceInOut.create') || route().current('productServiceInOut.edit'))">
                             <i class="nav-icon fas"></i><p>New</p>
                        </Link>
                    </li>
                </ul>
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
        <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
            	<slot name="header" />
            	<small v-if="$slots.back"><slot name="back" /></small>
            </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item" v-if="$slots.back"><slot name="back" /></li>
              <li class="breadcrumb-item active"><slot name="header" /></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      	<slot />
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <footer class="main-footer">
    Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      Version 3.2.0
    </div>
  </footer>

  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>

    </div>
</template>
