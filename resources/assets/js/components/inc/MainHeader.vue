<template>

<div class="wrapper">
    <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom fixed-top">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars blue"></i></a>
      </li>
    </ul>
      <SearchUsers />
      <SearchSuperpowers />
    <ul class="navbar-nav ml-auto">
      <li v-if="!loggedIn" class="nav-item d-none d-sm-inline-block">
        <router-link to="#" class="nav-link">Signin</router-link>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link to="/" class="brand-link">
      <img src="/storage/images/chachisha1.png" alt="Chachisha Logo" class="brand-image img-circle">
      <span class="brand-text font-weight-light">Chachisha.com</span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img v-if="auth.avatar == 'profile.png'" style="width:40px; height:40px; border-radius:50%;" :src="`/storage/images/${auth.avatar}`" class="img-circle elevation-2" alt="User Image">
          <img v-else style="width:40px; height:40px; border-radius:50%;" :src="`/storage/images/profile/${auth.avatar}`" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <span><router-link :to="`/users/${auth.id}`" class="d-block">{{auth.first_name}} {{auth.last_name}}</router-link></span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
              <router-link to="/" class="nav-link">
                  <i class="nav-icon fas fa-home green"></i>
                  <p>
                    Home
                  </p>
                </router-link>
          </li>

          <li class="nav-item">
            <router-link :to="`/users/${auth.id}`" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Profile
              </p>
            </router-link>
          </li>

          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog blue"></i>
              <p>
                Hot
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/superpowers/trending" class="nav-link">
                  <i class="fas fa-fire nav-icon orange"></i>
                  <p>Trending</p>
                </router-link>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                  <i class="fa fa-bell"></i>
                  <p>Notification</p>
                  <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                  <span class="dropdown-header">15 Notifications</span>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                    <i class="fa fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                    <i class="fa fa-users mr-2"></i> 8 new followers
                    <span class="float-right text-muted text-sm">12 hours</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                    <i class="fa fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
              </li>
            </ul>
          </li>

          <li v-if="loggedIn" class="nav-item">
            <router-link class="nav-link" to="/logout">
              <i class="nav-icon fa fa-power-off red"></i>
              <p>
                Logout
              </p>
            </router-link>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</div>

</template>

<script>
import SearchUsers from './SearchUsers';
import SearchSuperpowers from './SearchSuperpowers';
export default {
  name: 'main-header',
  components:{
    SearchUsers,
    SearchSuperpowers,
  },
  computed:{
    loggedIn(){
      return this.$store.getters.loggedIn
    },
    auth(){
        return this.$store.getters.auth
    }
  },
}
</script>

<style scoped>

</style>


