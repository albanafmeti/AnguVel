import {Component, Input, OnInit} from '@angular/core';
import {PostService} from '../services/post.service';

@Component({
  selector: 'app-featured-posts',
  templateUrl: './featured-posts.component.html'
})
export class FeaturedPostsComponent implements OnInit {

  posts: any[] = [];
  @Input() limit: number;

  constructor(private _postService: PostService) {
  }

  ngOnInit() {
    this.limit = this.limit ? this.limit : 6;
    this._postService.latest(this.limit, {
      featured: '1'
    }).subscribe(
      response => {
        this.posts = response.data;
      },
      error => console.log(<any>error)
    );
  }
}
