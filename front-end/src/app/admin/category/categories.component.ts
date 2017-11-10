import {Component, OnInit} from '@angular/core';
import {CategoryService} from '../../services/category.service';
import {NotificationsService} from 'angular2-notifications';

@Component({
  selector: 'app-categories',
  templateUrl: './categories.component.html'
})
export class CategoriesComponent implements OnInit {

  categories: any[];
  links: any = {};
  meta: any = {};
  prev: number;
  next: number;

  perPage = 10;
  keywords: string;

  constructor(private _categoryService: CategoryService, private notifications: NotificationsService) {
  }

  ngOnInit() {
    this.getCategories();
  }

  public onSearch(event): void {
    if (event.keyCode === 13) {
      this.getCategories({
        query: this.keywords
      });
    }
  }

  private getCategories(options?: any) {

    options = options ? options : {};
    options.perPage = (this.perPage) ? this.perPage : null;

    this._categoryService.getCategories(options).subscribe(
      response => {
        this.links = response.links;
        this.meta = response.meta;
        this.categories = response.data;
        this.updatePagination();
      },
      error => console.log(<any>error)
    );
  }

  deleteCategory(category) {
    if (category.posts_nr > 0) {
      return this.notifications.warn('Warning', 'You can not delete this category because it contains articles.');
    }

    this._categoryService.deleteCategory(category).subscribe(
      response => {
        if (response.success) {
          this.getCategories({
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
      this.getCategories({
        url: this.links.next
      });
      this.updatePagination();
    }
  }

  public prevPage() {
    if (this.links.prev) {
      this.getCategories({
        url: this.links.prev
      });
      this.updatePagination();
    }
  }

  public goToPage(page) {
    this.getCategories({
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
