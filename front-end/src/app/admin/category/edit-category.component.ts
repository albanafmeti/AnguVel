import {Component, OnInit} from '@angular/core';
import {CategoryService} from '../../services/category.service';
import {NotificationsService} from 'angular2-notifications';
import {ActivatedRoute, Router} from '@angular/router';

@Component({
  selector: 'app-edit-category',
  templateUrl: './edit-category.component.html'
})
export class EditCategoryComponent implements OnInit {

  category: any;
  slug: string;

  constructor(private _route: ActivatedRoute,
              private _categoryService: CategoryService,
              private notifications: NotificationsService,
              private _router: Router) {
  }

  ngOnInit() {
    this._route.params.subscribe(params => {
      this.slug = params['slug'];
      this.getCategory(params['slug']);
    });
  }

  updateCategory() {
    this._categoryService.updateCategory(this.slug, {
      name: this.category.name,
      slug: this.category.slug,
      description: this.category.description,
      order: this.category.order
    }).subscribe(response => {
      if (response.success) {

        this.notifications.success('Success', response.message);

        // Redirect to categories:
        this._router.navigate(['/admin/categories']);
        return true;
      }
      return this.notifications.error('Error', response.message);
    }, errorResponse => {
      if (errorResponse.status !== 422) {
        return this.notifications.warn('Warning', 'Something went wrong.');
      } else {
        const errors = errorResponse.error.errors;
        return this.notifications.warn('Warning', errors[Object.keys(errors)[0]]);
      }
    });
  }

  getCategory(slug: string) {
    this._categoryService.getCategory(slug).subscribe(
      response => {
        this.category = response.data;
      },
      error => console.log(<any>error)
    );
  }
}
