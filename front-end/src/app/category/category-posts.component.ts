import {Component, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {ActivatedRoute} from '@angular/router';
import {CategoryService} from '../services/category.service';
import {EmitterService} from '../services/emitter.service';

@Component({
  selector: 'app-category-posts',
  templateUrl: './category-posts.component.html'
})
export class CategoryPostsComponent implements OnInit {

  posts: any[] = [];
  slug: string;

  links: any = {};
  meta: any = {};
  prev: number;
  next: number;

  keywords: string;

  constructor(private _categoryService: CategoryService,
              private _route: ActivatedRoute,
              private titleService: Title) {
  }

  ngOnInit() {
    this._route.params.subscribe(params => {
      this.slug = params['slug'];
      this.postsByCategory(this.slug);
      this._categoryService.getCategory(this.slug).subscribe(response => this.titleService.setTitle(response.data.name + ' | Te Rejat | Kliko dhe Informohu'));
    });

    EmitterService.get('SEARCH_BLOG').subscribe(keywords => {
      this.keywords = keywords;
      this.postsByCategory(this.slug, {
        query: keywords
      });
    });
  }

  public onSearch(keywords: string): void {
    this.keywords = keywords;
    this.postsByCategory(this.slug, {
      query: keywords
    });
  }

  private postsByCategory(slug: string, options: any = {}) {
    this._categoryService.postsByCategory(slug, options).subscribe(
      response => {
        this.links = response.links;
        this.meta = response.meta;
        this.posts = response.data;
        this.updatePagination();
      },
      error => console.log(<any>error)
    );
  }

  /* Pagination functions */
  public nextPage() {
    if (this.links.next) {
      this.postsByCategory(this.slug, {
        url: this.links.next
      });
      this.updatePagination();
    }
  }

  public prevPage() {
    if (this.links.prev) {
      this.postsByCategory(this.slug, {
        url: this.links.prev
      });
      this.updatePagination();
    }
  }

  public goToPage(page) {
    this.postsByCategory(this.slug, {
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
