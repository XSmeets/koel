# Single Sign-On

Apart from the default authentication mechanism with email and password, users can also log in to Koel Plus via Single Sign-On (SSO).
Koel Plus supports the following SSO providers:

- Google OAuth
- OpenID Connect (generic/configurable provider)

## Google

To enable SSO with Google, you need to create a new OAuth client ID in the [Google Cloud Console](https://console.cloud.google.com/apis/credentials).
Pick "Web application" as the application type, and set the "Authorized redirect URIs" to `https://<your-koel-domain>/auth/google/callback`,
replacing `<your-koel-domain>` with your actual Koel domain.

<CaptionedImage :src="googleOauth" alt="Google OAuth">Create a new Google OAuth client ID</CaptionedImage>

Afterward, take note of the client ID and client secret values. You can then add them to your `.env` file:

```
SSO_GOOGLE_CLIENT_ID=<your-client-id>
SSO_GOOGLE_CLIENT_SECRET=<your-client-secret>
```

Finally, set the Google-hosted domain that you want to restrict logins. For example, if you only accept users from `your-koel.com`:

```
SSO_GOOGLE_HOSTED_DOMAIN=your-koel.com
```

Save the `.env` file and reload Koel. You should now see a "Log in with Google" button on the login page:

<img src="../assets/img/plus/login-form-google.webp" loading="lazy" style="width: 324px" alt="Google login button">

Clicking on the Google button will open a new window where you can log in with your Google account (make sure to allow pop-ups if you have a pop-up blocker enabled).

## OpenID Connect

Koel Plus supports generic OpenID Connect providers, which allows you to integrate with various identity providers such as Keycloak, Auth0, Okta, Azure AD, and others that support the OpenID Connect standard.

To enable SSO with an OpenID Connect provider, you need to:

1. **Create an OAuth/OIDC application** in your identity provider's admin console
2. **Set the redirect URI** to `https://<your-koel-domain>/auth/oidc/callback`
3. **Configure your `.env` file** with the following settings:

```
SSO_OIDC_CLIENT_ID=<your-client-id>
SSO_OIDC_CLIENT_SECRET=<your-client-secret>
SSO_OIDC_ISSUER=<your-oidc-issuer-url>
SSO_OIDC_NAME=<friendly-provider-name>
```

Where:
- `SSO_OIDC_CLIENT_ID`: The client ID from your OIDC provider
- `SSO_OIDC_CLIENT_SECRET`: The client secret from your OIDC provider  
- `SSO_OIDC_ISSUER`: The issuer URL of your OIDC provider (e.g., `https://your-provider.com/auth/realms/your-realm`)
- `SSO_OIDC_NAME`: A friendly name for the provider (optional, defaults to "OpenID Connect")

### Common Provider Examples

**Keycloak:**
```
SSO_OIDC_ISSUER=https://your-keycloak.com/auth/realms/your-realm
```

**Auth0:**
```
SSO_OIDC_ISSUER=https://your-tenant.auth0.com/
```

**Azure AD:**
```
SSO_OIDC_ISSUER=https://login.microsoftonline.com/{tenant-id}/v2.0
```

Save the `.env` file and reload Koel. You should now see a login button for your OpenID Connect provider on the login page.

## User Management

When a user logs in via SSO for the first time, a new user account will be created in Koel with the email address, name, avatar, and the SSO ID obtained from the SSO provider.
If, however, there's already an existing user with the same email address , Koel will merge the two accounts with a sensible merging strategy.

SSO users can update their name and avatar, but not their email address. Furthermore, a new user created via SSO does not have a password set and won't be able to log in via the email+password method.

<script lang="ts" setup>
import googleOauth from '../assets/img/plus/google-oauth.webp'
</script>
