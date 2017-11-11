import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {RouterModule} from '@angular/router';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {SelectModule} from 'ng2-select';
import {SlimLoadingBarModule} from 'ng2-slim-loading-bar';
import {FroalaEditorModule, FroalaViewModule} from 'angular-froala-wysiwyg';

import {LoginComponent} from './auth/login.component';
import {AuthService} from './auth/auth.service';
import {DashboardComponent} from './dashboard/dashboard.component';
import {AdminNavbarComponent} from './shared/admin-navbar.component';

import {AuthGuardService as AuthGuard} from './auth/auth-guard.service';
import {CategoriesComponent} from './category/categories.component';
import {AddCategoryComponent} from './category/add-category.component';
import {EditCategoryComponent} from './category/edit-category.component';
import {PostsComponent} from './posts/posts.component';
import {AddPostComponent} from './posts/add-post.component';
import {EditPostComponent} from './posts/edit-post.component';
import {Slim} from '../../assets/lib/slim/slim/slim.angular2';
import {FroalaService} from '../services/froala.service';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    RouterModule.forChild([
      {path: 'admin/login', component: LoginComponent},
      {path: 'admin/dashboard', component: DashboardComponent, canActivate: [AuthGuard]},

      {path: 'admin/posts/edit/:slug', component: EditPostComponent, canActivate: [AuthGuard]},
      {path: 'admin/posts/add', component: AddPostComponent, canActivate: [AuthGuard]},
      {path: 'admin/posts', component: PostsComponent, canActivate: [AuthGuard]},

      {path: 'admin/categories/edit/:slug', component: EditCategoryComponent, canActivate: [AuthGuard]},
      {path: 'admin/categories/add', component: AddCategoryComponent, canActivate: [AuthGuard]},
      {path: 'admin/categories', component: CategoriesComponent, canActivate: [AuthGuard]},

      {path: 'admin', redirectTo: 'admin/login', pathMatch: 'full'},
    ]),
    ConfirmationPopoverModule.forRoot({
      confirmButtonType: 'default'
    }),
    SelectModule,
    SlimLoadingBarModule.forRoot(),
    FroalaEditorModule.forRoot(),
    FroalaViewModule.forRoot()
  ],
  declarations: [
    LoginComponent,
    DashboardComponent,
    AdminNavbarComponent,
    EditCategoryComponent,
    AddCategoryComponent,
    CategoriesComponent,
    PostsComponent,
    AddPostComponent,
    EditPostComponent,
    Slim
  ],
  providers: [AuthService, AuthGuard, FroalaService],
  exports: [SlimLoadingBarModule]
})
export class AdminModule {
}
