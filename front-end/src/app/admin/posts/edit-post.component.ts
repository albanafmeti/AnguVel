import {Component, OnInit} from '@angular/core';
import {PostService} from '../../services/post.service';
import {CategoryService} from '../../services/category.service';
import {NotificationsService} from 'angular2-notifications';
import {ActivatedRoute, Router} from '@angular/router';

@Component({
  selector: 'app-edit-post',
  templateUrl: './edit-post.component.html'
})
export class EditPostComponent implements OnInit {

  post: any;
  slug: string;

  croppedImage: any;

  formData: any;

  slimOptions = {
    download: false,
    minSize: '750,500',
    instantEdit: true,
    rotate: true,
    didSave: this.saveImage.bind(this)
  };

  categories = [];
  selectedCategories = [];

  constructor(private _postService: PostService,
              private _categoryService: CategoryService,
              private notifications: NotificationsService,
              private router: Router,
              private _route: ActivatedRoute) {
  }

  ngOnInit() {
    this._route.params.subscribe(params => {
      this.slug = params['slug'];
      this.getPost(params['slug']);
    });

    this._categoryService.getCategories().subscribe(
      response => {

        const categories = [];
        for (const category of response.data) {
          categories.push({
            id: category.id,
            text: category.name
          });
        }
        this.categories = categories;
      },
      error => console.log(<any>error)
    );
  }

  updatePost() {

    this.formData = this.formData ? this.formData : new FormData();

    this.formData.append('title', this.post.title);
    this.formData.append('slug', this.post.slug);
    this.formData.append('small_content', this.post.small_content);
    this.formData.append('content', this.post.content);
    this.formData.append('author', this.post.author);
    this.formData.append('enabled', this.post.enabled ? 1 : 0);
    this.formData.append('featured', this.post.featured ? 1 : 0);

    if (this.croppedImage) {
      this.formData.append('croppedImage', this.croppedImage);
    }

    if (this.selectedCategories.length) {

      const categs = [];
      for (const selected of this.selectedCategories) {
        categs.push(selected.id);
      }

      this.formData.append('categories', JSON.stringify(categs));
    }

    this._postService.updatePost(this.slug, this.formData).subscribe(response => {

      if (response.success) {
        this.notifications.success('Success', response.message);

        // Redirect to posts:
        this.router.navigate(['/admin/posts']);
        return true;
      }
      return this.notifications.error('Error', response.message);
    }, errorResponse => {
      if (errorResponse.status !== 422) {
        return this.notifications.warn('Warning', 'Something went wrong.');
      } else {
        const errors = errorResponse.error.errors;
        return this.notifications.warn('Warning', errors[Object.keys(errors)[0]]);
      }
    });
  }

  onFileChange(event) {
    const fileList: FileList = event.target.files;

    if (fileList.length > 0) {
      const file: File = fileList[0];
      this.formData = new FormData();
      this.formData.append('image', file);
    } else {
      this.formData = null;
    }
  }

  getPost(slug: string) {
    this._postService.getPost(slug).subscribe(
      response => {
        this.post = response.data;
        for (const categ of this.post.categories) {
          this.selectedCategories.push({
            id: categ.category_id,
            text: categ.name
          });
        }
      },
      error => console.log(<any>error)
    );
  }

  saveImage(object) {
    this.croppedImage = JSON.stringify(object);
  }

  onCategoriesChanged(selectedCategories) {
    this.selectedCategories = selectedCategories;
  }
}
