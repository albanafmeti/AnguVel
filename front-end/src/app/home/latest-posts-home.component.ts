import {Component, Input, OnInit} from '@angular/core';
import {PostService} from '../services/post.service';

@Component({
  selector: 'app-latest-posts-home',
  templateUrl: './latest-posts-home.component.html'
})
export class LatestPostsHomeComponent implements OnInit {

  posts: any[] = [];
  @Input() limit: number;

  constructor(private _postService: PostService) {
  }

  ngOnInit() {
    this.limit = this.limit ? this.limit : 9;
    this._postService.latest(this.limit, {
      featured: '0'
    }).subscribe(
      response => {
        this.posts = response.data;
      },
      error => console.log(<any>error)
    );
  }
}
