import {Component, Input, OnInit} from '@angular/core';
import {EmitterService} from '../../services/emitter.service';
import {PostService} from '../../services/post.service';

@Component({
  selector: 'app-post-comments',
  templateUrl: './post-comments.component.html'
})
export class PostCommentsComponent implements OnInit {

  @Input() post: any;
  @Input() comments: any[] = [];

  constructor(private _postService: PostService) {
  }

  ngOnInit() {
    EmitterService.get('ADD_COMMENT').subscribe(event => {
      this._postService.getComments(this.post.slug).subscribe(
        response => {
          this.comments = response.data;
        },
        error => console.error(error)
      );
    });
  }
}
