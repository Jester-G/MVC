    </td>

    <td width="300px" class="sidebar">
        <div class="sidebarHeader">Menu</div>
        <ul>
            <li><a href="/ex4/ex5/another_MVC/www/">Main page</a></li>
            <?php if (empty($user)) :?>
            <li><a href="/ex4/ex5/another_MVC/www/users/login">Login</a></li>
            <li><a href="/ex4/ex5/another_MVC/www/users/register">Register</a></li>
            <?php endif; ?>
            <li><a href="/ex4/ex5/another_MVC/www/about-me">About me</a></li>
        </ul>
    </td>
    </tr>
    <tr>
        <td class="footer" colspan="2">All rights reserved &copy; Blog</td>
    </tr>
</table>

</body>
</html>