# Filament Peek Livewire Issue Reproduction

## Purpose

This repo is designed to be an easy replication of the issue from discussion comment
https://github.com/pboivin/filament-peek/discussions/107#discussioncomment-13725798

## Issue
Quite simply, it seems like if you have a TipTapEditor configured with a block which utilises a Livewire Component the Livewire Component will not be found when you try to save the form AFTER closing the preview
I believe this is because Livewire tracks the component by ID, and if it is removed from the page it is no longer tracked, hence isn't a fan for whatever reason.

My understanding is filament/tiptap has no problems with users having Livewire components in the content. 


## Installation Steps

1. Clone the repo (I use laravel herd)
2. Bog standard laravel install
3. Run `composer install`
4. Run `php artisan migrate`
5. Create a filament user `php artisan make:filament-user`
6. Login and head to the Article resource

My site was accessible via http://filament-peek-livewire-form-test.test/admin

You should get 3 tables, Articles, Forms, Form Submissions

### Steps to reproduce
Create an Article
- Title
- Slug
- Content

Add a Form Block to your TipTapContent

Save the article

Open Console Log

Hit Preview

Close Preview

Try save the Article

Notice how console log will show 
```text
Uncaught Component not found: X8DC1bpKodFGril7oq8x
```


