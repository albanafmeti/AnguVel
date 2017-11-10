import {Component, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {PostService} from '../services/post.service';
import {EmitterService} from '../services/emitter.service';
import {ActivatedRoute, Params} from '@angular/router';

@Component({
  selector: 'app-posts-list',
  templateUrl: './posts-list.component.html'
})
export class PostsListComponent implements OnInit {

  posts: any[] = [];
  links: any = {};
  meta: any = {};
  prev: number;
  next: number;

  keywords: string;

  constructor(private _postService: PostService,
              private activatedRoute: ActivatedRoute,
              private titleService: Title) {
    this.titleService.setTitle('Blog | Te Rejat | Kliko dhe Informohu');
  }

  ngOnInit() {
    this.getPosts();
    EmitterService.get('SEARCH_BLOG').subscribe(keywords => {
      this.keywords = keywords;
      this.getPosts({
        query: keywords
      });
    });

    this.activatedRoute.queryParams.subscribe((params: Params) => {
      if (params['q']) {
        this.keywords = params['q'];
        this.getPosts({
          query: params['q']
        });
      }
    });
  }

  public onSearch(keywords: string): void {
    this.keywords = keywords;
    this.getPosts({
      query: keywords
    });
  }

  private getPosts(options: any = null) {
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
