import {AfterViewInit, Component, OnInit} from '@angular/core';
import {CategoryService} from '../services/category.service';
import {EmitterService} from '../services/emitter.service';

declare let $: any;

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html'
})
export class HeaderComponent implements OnInit {

  keywords: string;
  categories: any[] = [];

  constructor(private _categoryService: CategoryService) {
  }

  ngOnInit() {
    this._categoryService.getCategories().subscribe(
      response => {
        this.categories = response.data;
      }, error => console.log(<any>error)
    );
  }

  public search() {
    EmitterService.get('SEARCH_BLOG').emit(this.keywords);
    $('.search-area').fadeOut();
  }

  public enterSearch(event) {
    if (event.keyCode === 13) {
      this.search();
    }
  }

}
