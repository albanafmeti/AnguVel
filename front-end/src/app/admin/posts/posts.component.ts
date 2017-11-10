import {Component, OnInit} from '@angular/core';
import {PostService} from '../../services/post.service';
import {NotificationsService} from 'angular2-notifications';
import {CategoryService} from '../../services/category.service';

@Component({
  selector: 'app-posts',
  templateUrl: './posts.component.html'
})
export class PostsComponent implements OnInit {
  posts: any[];
  links: any = {};
  meta: any = {};
  prev: number;
  next: number;

  perPage = 10;
  keywords: string;

  categories: any[];
  selectedCategoryId = '';

  constructor(private _postService: PostService,
              private _categoryService: CategoryService,
              private notifications: NotificationsService) {
  }

  ngOnInit() {
    this.getPosts();

    this._categoryService.getCategories().subscribe(
      response => {
        this.categories = response.data;
      }
    );
  }

  public onSearch(event): void {
    if (event.keyCode === 13) {
      this.getPosts({
        query: this.keywords,
        category_id: this.selectedCategoryId,
      });
    }
  }

  public OnCategoryChange() {
    this.getPosts({
      category_id: this.selectedCategoryId,
      query: this.keywords
    });
  }

  private getPosts(options?: any) {

    options = options ? options : {};
    options.perPage = (this.perPage) ? this.perPage : null;

    this._postService.getPosts(options).subscribe(
      response => {
        this.links = response.links;
        this.meta = response.meta;
        this.posts = response.data;
        this.updatePagination();
      },
      error => console.log(<any>error)
    );
  }

  deletePost(post) {
    this._postService.deletePost(post).subscribe(
      response => {
        if (response.success) {
          this.getPosts({
            query: this.keywords
          });
          return this.notifications.success('Success', response.message);
        }
        return this.notifications.error('Error', response.message);
      },
      errorResponse => {
        return this.notifications.warn('Warning', 'Something went wrong.');
      }
    );
  }

  /* Pagination functions */
  public nextPage() {
    if (this.links.next) {
      this.getPosts({
        url: this.links.next
      });
      this.updatePagination();
    }
  }

  public prevPage() {
    if (this.links.prev) {
      this.getPosts({
        url: this.links.prev
      });
      this.updatePagination();
    }
  }

  public goToPage(page) {
    this.getPosts({
      page: page,
      query: this.keywords
    });
  }

  public updatePagination() {
    this.prev = null;
    this.next = null;
    if (this.meta.current_page > 1) {
      this.prev = this.meta.current_page - 1;
    }
    if (this.meta.last_page > this.meta.current_page) {
      this.next = this.meta.current_page + 1;
    }
  }
}
