import {Component, Input, OnChanges, OnInit} from '@angular/core';
import {PostService} from '../services/post.service';

@Component({
  selector: 'app-posts-nav',
  templateUrl: './posts-nav.component.html'
})
export class PostsNavComponent implements OnInit, OnChanges {

  @Input() post: any;
  previous: any;
  next: any;

  constructor(private _postService: PostService) {
  }

  ngOnInit() {
    this.getData();
  }

  ngOnChanges() {
    if (this.post) {
      this.getData();
    }
  }

  getData() {
    this._postService.alternativePosts(this.post.slug).subscribe(
      response => {
        this.previous = response.previous;
        this.next = response.next;
      },
      error => console.log(<any>error)
    );
  }
}
