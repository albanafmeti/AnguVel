import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {HttpClientModule} from '@angular/common/http';
import {RouterModule} from '@angular/router';
import {FormsModule} from '@angular/forms';
import {SimpleNotificationsModule} from 'angular2-notifications';
import {CookieModule} from 'ngx-cookie';

import {AppComponent} from './app.component';
import {FooterComponent} from './shared/footer.component';
import {HeaderComponent} from './shared/header.component';
import {PostsListComponent} from './posts/posts-list.component';
import {SearchBlogComponent} from './shared/search-blog.component';
import {LatestPostsComponent} from './shared/latest-posts.component';
import {CategoriesComponent} from './shared/categories.component';
import {PostDetailsComponent} from './posts/post-details.component';
import {HomeComponent} from './home/home.component';
import {PostCommentsComponent} from './posts/comments/post-comments.component';
import {AddCommentComponent} from './posts/comments/add-comment.component';
import {CategoryPostsComponent} from './category/category-posts.component';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {FeaturedPostsComponent} from './home/featured-posts.component';
import {LatestPostsHomeComponent} from './home/latest-posts-home.component';
import {NewsletterComponent} from './home/newsletter.component';
import {PostsNavComponent} from './posts/posts-nav.component';
import {AdminModule} from './admin/admin.module';


@NgModule({
  declarations: [
    AppComponent,
    FooterComponent,
    HeaderComponent,
    PostsListComponent,
    SearchBlogComponent,
    LatestPostsComponent,
    CategoriesComponent,
    PostDetailsComponent,
    HomeComponent,
    FeaturedPostsComponent,
    LatestPostsHomeComponent,
    NewsletterComponent,
    PostCommentsComponent,
    AddCommentComponent,
    CategoryPostsComponent,
    PostsNavComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    FormsModule,
    RouterModule.forRoot([
      {path: 'blog', component: PostsListComponent},
      {path: 'post/:slug', component: PostDetailsComponent},
      {path: 'category/:slug', component: CategoryPostsComponent},
      {path: 'home', component: HomeComponent},
      {path: '', redirectTo: 'home', pathMatch: 'full'},
      {path: '**', redirectTo: 'home', pathMatch: 'full'}
    ]),
    BrowserAnimationsModule,
    SimpleNotificationsModule.forRoot(),
    CookieModule.forRoot(),
    AdminModule,
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {
}
