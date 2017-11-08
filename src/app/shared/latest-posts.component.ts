import {Component, Input, OnInit} from '@angular/core';
import {PostService} from '../services/post.service';

@Component({
  selector: 'app-latest-posts',
  templateUrl: './latest-posts.component.html'
})
export class LatestPostsComponent implements OnInit {

  posts: any[] = [];
  @Input() limit: number;

  constructor(private _postService: PostService) {
  }

  ngOnInit() {

    this.limit = this.limit ? this.limit : 6;
    this._postService.latest(this.limit).subscribe(
      response => {
        this.posts = response.data;
      },
      error => console.log(<any>error)
    );
  }

}
