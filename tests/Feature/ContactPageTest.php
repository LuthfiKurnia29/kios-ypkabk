<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactPageTest extends TestCase
{
    /**
     * Test that the contact page is accessible and renders the expected view.
     */
    public function test_contact_page_is_accessible_and_renders_successfully(): void
    {
        $response = $this->get('/kontak');

        $response->assertOk();
        $response->assertViewIs('user.pages.kontak');
        $response->assertSee('Kontak');
        $response->assertSee('Sentra Sidoarjo');
        $response->assertSee('Sentra Mojokerto');
        $response->assertSee('Jl. Wonocolo Pabrik Kulit No. 34');
        $response->assertSee('Jl. Raya Brangkal No. 12');
        $response->assertSee('kios@pedulikasihabk.org');
        $response->assertSee('+62 812-3456-7890');
        $response->assertSee('Formulir Kontak');
        $response->assertSee('Pertanyaan yang Sering Diajukan');
    }

    /**
     * Test that the contact form elements exist on the page.
     */
    public function test_contact_page_contains_form_fields(): void
    {
        $response = $this->get('/kontak');

        $response->assertOk();
        $response->assertSee('id="contact-form"', false);
        $response->assertSee('id="contact-category"', false);
        $response->assertSee('id="contact-name"', false);
        $response->assertSee('id="contact-phone"', false);
        $response->assertSee('id="contact-email"', false);
        $response->assertSee('id="contact-message"', false);
    }
}
