import {Component, OnInit} from '@angular/core';
import {CategoryService} from '../services/category.service';

@Component({
  selector: 'app-categories',
  templateUrl: './categories.component.html'
})
export class CategoriesComponent implements OnInit {

  categories: any[] = [];

  constructor(private _categoryService: CategoryService) {
  }

  ngOnInit() {
    this._categoryService.getCategories().subscribe(
      response => this.categories = response.data,
      error => console.log(<any>error)
    );
  }
}
