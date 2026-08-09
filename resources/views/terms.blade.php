@extends('layouts.page')

@section('title', 'Memoriq - Terms of Service')
@section('heading', 'Terms of Service')
@section('updated', 'Last updated: June 7, 2026')

@section('content')
    <p>
        These terms cover use of the hosted Memoriq service at memoriq.me and related
        hosted services. Memoriq is a private AI memory vault for saving, organizing,
        searching, exporting, and deleting your own AI conversations.
    </p>

    <h2>Early software</h2>
    <p>
        Memoriq is early-stage software. The service, extension, import tools, and
        provider capture logic may change, break, or be discontinued. You should keep
        your own exports or backups of anything important.
    </p>

    <h2>Your account and encryption keys</h2>
    <p>
        You are responsible for your account access, encryption password, recovery key,
        and exported backups. Because Memoriq is designed around end-to-end encryption,
        we cannot read or recover encrypted vault contents if you lose the required keys.
    </p>

    <h2>Your content</h2>
    <p>
        You keep ownership of the conversations and data you save. You are responsible
        for making sure you have the right to save, store, export, and delete that data.
        Do not use Memoriq for unlawful content or activity.
    </p>

    <h2>Extension capture</h2>
    <p>
        The browser extension uses best-effort extraction from supported AI provider
        pages. Providers can change their interfaces at any time, so capture may miss
        content, duplicate content, save formatting imperfectly, or stop working until
        updated.
    </p>

    <h2>Availability and data loss</h2>
    <p>
        We try to keep the hosted service reliable, but Memoriq is provided without a
        guarantee of uninterrupted availability or loss-free operation. Use export and
        self-hosting options if you need stronger control over backups and availability.
    </p>

    <h2>Open source and self-hosting</h2>
    <p>
        The Memoriq app and extension are licensed under the GNU Affero General Public
        License v3.0 only. You may study, self-host, modify, and share the software under
        that license. Independent self-hosted deployments are operated by whoever runs
        them, not by the hosted memoriq.me service.
    </p>

    <h2>Memoriq name and logo</h2>
    <p>
        “Memoriq” and the Memoriq logo are used as project trademarks. The AGPL license
        applies to the source code, but it does not grant permission to use the Memoriq
        name or logo to publish unofficial apps, extensions, hosted services, or other
        products in a way that suggests they are official or endorsed.
    </p>

    <h2>Changes</h2>
    <p>
        These terms may be updated as the product matures. Material changes will be
        reflected on this page.
    </p>
@endsection
