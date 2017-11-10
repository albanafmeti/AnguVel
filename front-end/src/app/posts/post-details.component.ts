import {Component, Inject, OnChanges, OnInit} from '@angular/core';
import {Title, Meta} from '@angular/platform-browser';
import {DOCUMENT} from '@angular/common';
import {PostService} from '../services/post.service';
import {ActivatedRoute, Router} from '@angular/router';
import {EmitterService} from '../services/emitter.service';

@Component({
  selector: 'app-post-details',
  templateUrl: './post-details.component.html'
})
export class PostDetailsComponent implements OnInit {

  post: any;

  currentUrl: string;

  constructor(private _postService: PostService,
              private _route: ActivatedRoute,
              private router: Router,
              private titleService: Title,
              private meta: Meta,
              @Inject(DOCUMENT) private document: Document) {

    this.currentUrl = this.document.location.href;
  }

  ngOnInit() {
    this._route.params.subscribe(params => {
      this.getPost(params['slug']);
    });

    EmitterService.get('SEARCH_BLOG').subscribe(keywords => {
      this.router.navigateByUrl('/blog?q=' + keywords);
    });
  }

  getPost(slug: string) {
    this._postService.getPost(slug).subscribe(
      response => {
        this.post = response.data;

        this.currentUrl = this.document.location.href;

        this.titleService.setTitle(this.post.title + ' | Te Rejat');
        this.meta.addTag({name: 'og:url', content: this.document.location.href});
        this.meta.addTag({name: 'og:type', content: 'article'});
        this.meta.addTag({name: 'og:title', content: this.post.title});
        this.meta.addTag({name: 'og:description', content: this.post.small_content});
        this.meta.addTag({name: 'og:image', content: this.post.thumb_lg});
      },
      error => console.log(<any>error)
    );
  }
}
